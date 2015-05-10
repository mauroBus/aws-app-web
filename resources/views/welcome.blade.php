<html>
	<head>
		<title>Best Talent</title>

        <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
                font-family: 'Droid Sans', sans-serif;
			}

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 96px;
				margin-bottom: 40px;
			}

			.quote {
                font-family: 'Droid Sans', sans-serif;
				font-size: 24px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title"><img src="/img/besttalent.png"/></div>
				<div class="quote">{{ Inspiring::quote() }}</div>
                <br/>
                <button type="button" class="btn btn-primary" onclick="javascript:window.location='/home';">Login</button>
			</div>
		</div>
	</body>
</html>
