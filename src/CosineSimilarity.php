<?php

class PdfParser{

  public static function parseFile($filename){
    $content = file_get_contents($filename);
    return self::extractText($content);
  }
 
  public static function parseContent($content){
    return self::extractText($content);
  }
  
  protected static function extractText($data){
    /**
     * Split apart the PDF document into sections. We will address each
     * section separately.
     */
    $a_obj    = self::getDataArray($data, 'obj', 'endobj');
    $j        = 0;
    $a_chunks = array();
    /**
     * Attempt to extract each part of the PDF document into a 'filter'
     * element and a 'data' element. This can then be used to decode the
     * data.
     */
    foreach ($a_obj as $obj) {
      $a_filter = self::getDataArray($obj, '<<', '>>');
      if (is_array($a_filter) && isset($a_filter[0])) {
        $a_chunks[$j]['filter'] = $a_filter[0];
        $a_data = self::getDataArray($obj, 'stream', 'endstream');
        if (is_array($a_data) && isset($a_data[0])) {
          $a_chunks[$j]['data'] = trim(substr($a_data[0], strlen('stream'), strlen($a_data[0]) - strlen('stream') - strlen('endstream')));
        }
        $j++;
      }
    }
    $result_data = null;
    // decode the chunks
    foreach ($a_chunks as $chunk) {
      // Look at each chunk decide if we can decode it by looking at the contents of the filter
      if(isset($chunk['data'])) {
        // look at the filter to find out which encoding has been used
        if (strpos($chunk['filter'], 'FlateDecode') !== false) {
          // Use gzuncompress but suppress error messages.
          $data =@ gzuncompress($chunk['data']);
        } else {
          $data = $chunk['data'];
        }
        if (trim($data) != '') {
          // If we got data then attempt to extract it.
          $result_data .= ' ' . self::extractTextElements($data);
        }
      }
    }
    /**
     * Make sure we don't have large blocks of white space before and after
     * our string. Also extract alphanumerical information to reduce
     * redundant data.
     */
    if (trim($result_data) == '') {
      return null;
    } else {
      // Optimize hyphened words
      $result_data = preg_replace('/\s*-[\r\n]+\s*/', '', $result_data);
      $result_data = preg_replace('/\s+/', ' ', $result_data);
      return $result_data;
    }
  }
  protected static function extractTextElements($content)
  {
    if (strpos($content, '/CIDInit') === 0) {
      return '';
    }
    $text  = '';
    $lines = explode("\n", $content);
    foreach ($lines as $line) {
      $line = trim($line);
      $matches = array();
      // Parse each lines to extract command and operator values
      if (preg_match('/^(?<command>.*[\)\] ])(?<operator>[a-z]+[\*]?)$/i', $line, $matches)) {
        $command = trim($matches['command']);
        // Convert octal encoding
        $found_octal_values = array();
        preg_match_all('/\\\\([0-9]{3})/', $command, $found_octal_values);
        foreach($found_octal_values[0] as $value) {
          $octal = substr($value, 1);
          if (intval($octal) < 40) {
            // Skips non printable chars
            $command = str_replace($value, '', $command);
          } else {
            $command = str_replace($value, chr(octdec($octal)), $command);
          }
        }
        // Removes encoded new lines, tabs, ...
        $command = preg_replace('/\\\\[\r\n]/', '', $command);
        $command = preg_replace('/\\\\[rnftb ]/', ' ', $command);
        // Force UTF-8 charset
        $encoding = mb_detect_encoding($command, array('ASCII', 'UTF-8', 'Windows-1252', 'ISO-8859-1'));
        if (strtoupper($encoding) != 'UTF-8') {
          if ($decoded = @iconv('CP1252', 'UTF-8//TRANSLIT//IGNORE', $command)) {
            $command = $decoded;
          }
        }
        // Removes leading spaces
        $operator = trim($matches['operator']);
      } else {
        $command = $line;
        $operator = '';
      }
      // Handle main operators
      switch ($operator) {
        // Set character spacing.
        case 'Tc':
          break;
        // Move text current point.
        case 'Td':
          $values = explode(' ', $command);
          $y = array_pop($values);
          $x = array_pop($values);
          if ($x > 0) {
            $text .= ' ';
          }
          if ($y < 0) {
            $text .= ' ';
          }
          break;
        // Move text current point and set leading.
        case 'TD':
          $values = explode(' ', $command);
          $y = array_pop($values);
          if ($y < 0) {
            $text .= "\n";
          }
          break;
        // Set font name and size.
        case 'Tf':
          $text.= ' ';
          break;
        // Display text, allowing individual character positioning
        case 'TJ':
          $start = mb_strpos($command, '[', null, 'UTF-8') + 1;
          $end   = mb_strrpos($command, ']', null, 'UTF-8');
          $text.= self::parseTextCommand(mb_substr($command, $start, $end - $start, 'UTF-8'));
          break;
        // Display text.
        case 'Tj':
          $start = mb_strpos($command, '(', null, 'UTF-8') + 1;
          $end   = mb_strrpos($command, ')', null, 'UTF-8');
          $text.= mb_substr($command, $start, $end - $start, 'UTF-8'); // Removes round brackets
          break;
        // Set leading.
        case 'TL':
        // Set text matrix.
        case 'Tm':
//          $text.= ' ';
          break;
        // Set text rendering mode.
        case 'Tr':
          break;
        // Set super/subscripting text rise.
        case 'Ts':
          break;
        // Set text spacing.
        case 'Tw':
          break;
        // Set horizontal scaling.
        case 'Tz':
          break;
        // Move to start of next line.
        case 'T*':
          $text.= "\n";
          break;
        // Internal use
        case 'g':
        case 'gs':
        case 're':
        case 'f':
        // Begin text
        case 'BT':
        // End text
        case 'ET':
          break;
        case '':
          break;
        default:
      }
    }
    $text = str_replace(array('\\(', '\\)'), array('(', ')'), $text);
    return $text;
  }
  
  protected static function parseTextCommand($text, $font_size = 0) {
    $result = '';
    $cur_start_pos = 0;
    while (($cur_start_text = mb_strpos($text, '(', $cur_start_pos, 'UTF-8')) !== false) {
      // New text element found
      if ($cur_start_text - $cur_start_pos > 8) {
        $spacing = ' ';
      } else {
        $spacing_size = mb_substr($text, $cur_start_pos, $cur_start_text - $cur_start_pos, 'UTF-8');
        if ($spacing_size < -50) {
          $spacing = ' ';
        } else {
          $spacing = '';
        }
      }
      $cur_start_text++;
      $start_search_end = $cur_start_text;
      while (($cur_start_pos = mb_strpos($text, ')', $start_search_end, 'UTF-8')) !== false) {
        if (mb_substr($text, $cur_start_pos - 1, 1, 'UTF-8') != '\\') {
          break;
        }
        $start_search_end = $cur_start_pos + 1;
      }
      // something wrong happened
      if ($cur_start_pos === false) {
        break;
      }
      // Add to result
      $result .= $spacing . mb_substr($text, $cur_start_text, $cur_start_pos - $cur_start_text, 'UTF-8');
      $cur_start_pos++;
    }
    return $result;
  }
  
  protected static function getDataArray($data, $start_word, $end_word){
    $start     = 0;
    $end       = 0;
    $a_results = array();
    while ($start !== false && $end !== false) {
      $start = strpos($data, $start_word, $end);
      $end   = strpos($data, $end_word, $start);
      if ($end !== false && $start !== false) {
        // data is between start and end
        $a_results[] = substr($data, $start, $end - $start + strlen($end_word));
      }
    }
    return $a_results;
  }
}

class CosineSimilarity{

    public function cosinusTokens(array $tokensA, array $tokensB) {
        $dotProduct = $normA = $normB = 0;
        $uniqueTokensA = $uniqueTokensB = array();
        $uniqueMergedTokens = array_unique(array_merge($tokensA, $tokensB));

        foreach ($tokensA as $token) $uniqueTokensA[$token] = 0;
        foreach ($tokensB as $token) $uniqueTokensB[$token] = 0;

        foreach ($uniqueMergedTokens as $token) {
            $x = isset($uniqueTokensA[$token]) ? 1 : 0;
            $y = isset($uniqueTokensB[$token]) ? 1 : 0;
            $dotProduct += $x * $y;
            $normA += $x;
            $normB += $y;
        }

        return ($normA * $normB) != 0 ? $dotProduct / sqrt($normA * $normB) : 0;
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PDS";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error) {
  echo "Connection Failed ";
    die("Connection failed: " . $conn->connect_error);
}

$ID = $_GET["id"];
$File = "Documents/".$ID.'.pdf';
$Report = "Reports/".$ID.".html";

$pt = new PdfParser();
$cs = new CosineSimilarity();
$str1 = $pt->parseFile($File);

$id = 1;
$status = 1;
$error = 0.5;

$myfile = fopen($Report, "w") or die("Unable to open file!");
fwrite($myfile,"<!DOCTYPE html>\n");
fwrite($myfile,"<html lang='en'>\n");
fwrite($myfile,"<head>\n");
  fwrite($myfile,"<title>Report</title>\n");
  fwrite($myfile,"<meta charset='utf-8'>");
  fwrite($myfile,"<meta name='viewport' content='width=device-width, initial-scale=1'>\n");
  fwrite($myfile,"<link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>\n");
  fwrite($myfile,"<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>\n");
  fwrite($myfile,"<script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>\n");
fwrite($myfile,"</head>\n");
fwrite($myfile,"<body>\n");

fwrite($myfile,"<div class='container'>\n");
  fwrite($myfile,"<h3 style='text-align: center;'>Report of</h3>\n");
  fwrite($myfile,"<h1 style='text-align: center;'>Submission ID: $ID</h1>\n");         
  fwrite($myfile,"<table class='table'>\n");
    fwrite($myfile,"<thead>\n");
      fwrite($myfile,"<tr>\n");
        fwrite($myfile,"<th>ID</th>\n");
        fwrite($myfile,"<th>Title</th>\n");
        fwrite($myfile,"<th>Submitted By</th>\n");
        fwrite($myfile,"<th>Similarity</th>\n");
        fwrite($myfile,"<th>Verdict</th>\n");
      fwrite($myfile,"</tr>\n");
    fwrite($myfile,"</thead>\n");
    fwrite($myfile,"<tbody>\n");

$StatusVal = 2;

while($id < $ID){
    $sql = "SELECT * FROM Submission WHERE SubID = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $title = $row["Title"];
    $email = $row["Email"];
    $profile_link = "http://localhost/ViewProfile.php?email=".$email;

    $SQL = "SELECT Name FROM Student WHERE Email = '$email'";
    $RES = $conn->query($SQL);
    $ROW = $RES->fetch_assoc();
    $NAME = $ROW["Name"];

    $file = "Documents/".$id.'.pdf';
    $str2 = $pt->parseFile($file);

    $arr1 = explode(" ",$str1);
    $arr2 = explode(" ",$str2);

    $res = $cs->cosinusTokens($arr1, $arr2);
    if($res > 0.70){
      $verdict = "Rejected";
      $val = 0;
    }
    else if($res > 0.35){
      $verdict = "Considerable";
      $val = 1;
    }
    else{
      $verdict = "Accepted";
      $val = 2;
    }
    $StatusVal = min($StatusVal,$val);

    $res = intval($res * 100);

     fwrite($myfile,"<tr>\n");
        fwrite($myfile,"<td>".$id."</td>\n");
        fwrite($myfile,"<td>".$title."</td>\n");
        fwrite($myfile,"<td><a href = $profile_link>$NAME</a></td>\n");
        fwrite($myfile,"<td>".$res."%</td>\n");
        fwrite($myfile,"<td>".$verdict."</td>\n");
      fwrite($myfile,"</tr>\n");

    $id = $id + 1;
}

    fwrite($myfile,"</tbody>\n");
  fwrite($myfile,"</table>\n");
fwrite($myfile,"</div>\n");

fwrite($myfile, "</body>\n");
fwrite($myfile, "</html>\n");

fclose($myfile);

if($StatusVal == 0) $status = "Rejected";
else if($StatusVal == 1) $status = "Considerable";
else $status = "Accepted";

$sql = "UPDATE Submission SET Status = '$status' where SubID ='$ID'";
$conn->query($sql);

$conn->close();
header("refresh:0; url=SubmissionsViewByStudent.php");
?>

</body>
</html>