<?php  
if ($_GET['q'] && $_GET['maxResults']) {  
  // Call set_include_path() as needed to point to your client library.  
  require_once ('google-api-php-client/src/Google_Client.php');  
  require_once ('google-api-php-client/src/contrib/Google_YouTubeService.php');  
  
  /* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the 
  Google APIs Console <http://code.google.com/apis/console#access> 
  Please ensure that you have enabled the YouTube Data API for your project. */  
  $DEVELOPER_KEY = 'AIzaSyDk5QsN-klumf3nKQlPX969tzmapLffjGE';  
  
  $client = new Google_Client();  
  $client->setDeveloperKey($DEVELOPER_KEY);  
  
  $youtube = new Google_YoutubeService($client);  
  
  try {  
    $searchResponse = $youtube->search->listSearch('id,snippet', array(  
      'q' => $_GET['q'],  
      'maxResults' => $_GET['maxResults'],  
    ));  
  
    $videos = '';  
    $channels = '';  
  
    foreach ($searchResponse['items'] as $searchResult) {  
      switch ($searchResult['id']['kind']) {  
        case 'youtube#video':  
          $videos .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],  
            $searchResult['id']['videoId']."<a href=http://www.youtube.com/watch?v=".$searchResult['id']['videoId']." target=_blank >   Watch This Video</a>");  
          break;  
        case 'youtube#channel':  
          $channels .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],  
            $searchResult['id']['channelId']);  
          break;  
       }  
    }  
  
   } catch (Google_ServiceException $e) {  
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',  
      htmlspecialchars($e->getMessage()));  
  } catch (Google_Exception $e) {  
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',  
      htmlspecialchars($e->getMessage()));  
  }  
}  
?>  
  

<html>  
  <head>  
    <title>YouTube Search</title>  
    <link href="css/style.css" type="text/css" rel="stylesheet" />
    <link href="//www.w3resource.com/includes/bootstrap.css" rel="stylesheet">  
<style type="text/css">  
body{margin-top: 50px; margin-left: 50px} 
dt {
width: 110px;
float: left;
line-height: 27px;
} 

</style>  
  </head>  
  <body>  
  <div  id="container">
    <div id="youtube_results">
    <a href="index.php" style="position: relative;top: -35px;left: -25px;">« Back to Home Page</a>
      <form method="GET">  
      <dt>Searched Term:</dt>
      <dd> <input type="search" id="q" name="q" value="<?php echo $_GET['q']; ?>">  </dd>   
      <dt> Max Results:</dt>
      <dd> <input style="height: 28px;width: 205px;" type="number" id="maxResults" name="maxResults" min="1" max="50" step="1" value="<?php echo $_GET['maxResults']; ?>">  </dd> 
      <input type="submit" value="Search">  
  </form>  
  <h3>Videos</h3>  
      <ul><?php echo $videos; ?></ul>  
      <h3>Channels</h3>  
      <ul><?php echo $channels; ?></ul>  
      </div>
  </div>
</body>  
</html>  