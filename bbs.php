<?php 
// ここにDBに登録する処理を記述する
$dsn = 'mysql:dbname=oneline_bbs;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->query('SET NAMES utf8');

// echo $_POST['nickname'];

if (!empty($_POST)) { // 送信ボタンが押されたとき
		// 送信されたデータを登録する
		$sql = 'INSERT INTO `posts` SET `id`=NULL,
																		`nickname`=?,
																		`comment`=?,
																		`created`=NOW()
																		';
		$data = array($_POST['nickname'], $_POST['comment']);

		$stmt = $dbh->prepare($sql);
		$stmt->execute($data); // object型

		// ページのリロード処理
		header('Location: bbs_no_css.php');
		exit();
}

$sql = 'SELECT * FROM `posts` ORDER BY `created` DESC';
// ORDER BY句
// 指定したカラムの昇順(ASC)もしくは降順(DESC)でデータ取得順を指定する(初期設定ではidのASC)
$stmt = $dbh->prepare($sql);
$stmt->execute(); // object型

// 空の配列を定義
$posts = array();

// 繰り返し処理
while (true) {
		$record = $stmt->fetch(PDO::FETCH_ASSOC); // array型
		if ($record == false) {
				break;
		}
		// echo $record['nickname'] . ' - ' . $record['created'];
		// echo '<br>';

		// whileの外に用意した配列に入れる
		$posts[] = $record;
		// 配列名のあとに[]をつけると最後の段を指定する
}

echo count($posts);
echo '<br>';

// 配列を元に繰り返したいときの構文
foreach ($posts as $post) {
		// $post = $posts[n];
		echo $post['nickname'] . '<br>';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <!-- ナビゲーションバー -->
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-linux"></i> Oneline bbs</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <!-- Bootstrapのcontainer -->
  <div class="container">
    <!-- Bootstrapのrow -->
    <div class="row">

      <!-- 画面左側 -->
      <div class="col-md-4 content-margin-top">
        <!-- form部分 -->
        <form action="bbs.php" method="post">
          <!-- nickname -->
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="nickname" class="form-control" id="validate-text" placeholder="nickname" required>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- つぶやくボタン -->
          <button type="submit" class="btn btn-primary col-xs-12" disabled>つぶやく</button>
        </form>
      </div>

      <!-- 画面右側 -->
      <div class="col-md-8 content-margin-top">
        <div class="timeline-centered">
          <?php foreach($posts as $post) { ?>
	          <article class="timeline-entry">
	              <div class="timeline-entry-inner">
	                  <div class="timeline-icon bg-success">
	                      <i class="entypo-feather"></i>
	                      <i class="fa fa-cogs"></i>
	                  </div>
	                  <div class="timeline-label">
	                      <h2><a href="#"><?php echo $post['nickname']; ?></a> <span><?php echo $post['created']; ?></span></h2>
	                      <p><?php echo $post['comment']; ?></p>
	                  </div>
	              </div>
	          </article>
          <?php } ?>

          <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                      <i class="entypo-flight"></i> +
                  </div>
              </div>
          </article>
        </div>
      </div>

    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>
</body>
</html>



