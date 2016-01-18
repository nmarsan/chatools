<?php  require_once('./init.php'); ?>
<?php if(controler($_POST+$_GET))header('location:index.php'); ?>
<!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>	
		<title>Chatools</title><base href="http://pyxis.de-mok.com/" />
		<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>		
		<script type="text/javascript" src="js/jquery-ui-1.8.1.custom.min.js"></script>		
		<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>		
		 <script type="text/javascript" src="js/fonctions.js"></script>		
		 <link type="text/css" rel="stylesheet" media="all" href="css/main.css" />
		  <link type="text/css" rel="stylesheet" media="all" href="css/cupertino/jquery-ui-1.8.1.custom.css" />
		 <link type="text/css" rel="stylesheet" media="all" href="css/modules.css" />
		
		 
		 <link href="favicon.png" rel="icon" type="image/png" />

		 <link rel="alternate" type="application/rss+xml" title="actu" href="flux.php" />
		 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 
		 
	</head>
	<body>
		<div id="global">
			<div id="site">		
			
			<p class="floatRight actionAll">Action sur toutes les semaines : <a href="#" id="open_all"><img src="images/plusS.png" alt="" /></a><a href="#" id="close_all"><img src="images/minusS.png" alt="" /></a></p>
			<h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CHATOOLS </h1>
			
				<div class="contenu">		
				<div class="formulaire form_aj">
						<form action="index.php" method="post" class="left">
							<p class="test comment">Ajouter une journée</p>
							<p class="text" >
								<label for="date_jour">Date : </label>
								<input type="text" name="date_jour" id="date_jour" value="" />
								<input type="hidden" name="action" value="ajout_jour"/>
								<input type="submit" name="submit" value="Ajouter" />
							</p>						
									
						</form>
                                    <div class="clear"></div>			
					</div>
					<p class="floatRight">Années :<?php print buildHistory() ?></p>
				<?php print renderListePost() ?>				
					
				</div>
			</div>
		</div>
		
	</body>
</html>


