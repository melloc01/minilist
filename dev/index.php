
<head>
	<meta charset=utf8>
	<title>Revisão Sistemática</title>
	<style type='text/css'>
		body{
			color:#333;
			font-family : verdana,sans-serif;		
		}
	</style>
</head>

<?php  
	function w1250_to_utf8($text) {
    // map based on:
    // http://konfiguracja.c0.pl/iso02vscp1250en.html
    // http://konfiguracja.c0.pl/webpl/index_en.html#examp
    // http://www.htmlentities.com/html/entities/
    $map = array(
        chr(0x8A) => chr(0xA9),
        chr(0x8C) => chr(0xA6),
        chr(0x8D) => chr(0xAB),
        chr(0x8E) => chr(0xAE),
        chr(0x8F) => chr(0xAC),
        chr(0x9C) => chr(0xB6),
        chr(0x9D) => chr(0xBB),
        chr(0xA1) => chr(0xB7),
        chr(0xA5) => chr(0xA1),
        chr(0xBC) => chr(0xA5),
        chr(0x9F) => chr(0xBC),
        chr(0xB9) => chr(0xB1),
        chr(0x9A) => chr(0xB9),
        chr(0xBE) => chr(0xB5),
        chr(0x9E) => chr(0xBE),
        chr(0x80) => '&euro;',
        chr(0x82) => '&sbquo;',
        chr(0x84) => '&bdquo;',
        chr(0x85) => '&hellip;',
        chr(0x86) => '&dagger;',
        chr(0x87) => '&Dagger;',
        chr(0x89) => '&permil;',
        chr(0x8B) => '&lsaquo;',
        chr(0x91) => '&lsquo;',
        chr(0x92) => '&rsquo;',
        chr(0x93) => '&ldquo;',
        chr(0x94) => '&rdquo;',
        chr(0x95) => '&bull;',
        chr(0x96) => '&ndash;',
        chr(0x97) => '&mdash;',
        chr(0x99) => '&trade;',
        chr(0x9B) => '&rsquo;',
        chr(0xA6) => '&brvbar;',
        chr(0xA9) => '&copy;',
        chr(0xAB) => '&laquo;',
        chr(0xAE) => '&reg;',
        chr(0xB1) => '&plusmn;',
        chr(0xB5) => '&micro;',
        chr(0xB6) => '&para;',
        chr(0xB7) => '&middot;',
        chr(0xBB) => '&raquo;',
    );
    return html_entity_decode(mb_convert_encoding(strtr($text, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');
}

function slug($z){
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', '-', $z);
    return trim($z, '-');
}

?>

<body style="text-align:center;">
	<h1 style='margin-top:50px;'>
		Tratamento da lista de artigos da Revisão Sistemática 
	</h1>
	<div style='border: 1px solid orange;  padding: 50px; text-align:left; color:#755C29; background:#FFDB73; display:table; width:60%; margin: 25px auto;'> 
		<h4> Aten&ccedil;&atilde;o, os arquivos fornecidos devem ter a extens&atilde;o <b>.txt</b> </h4> 
		<p> Como fazer para passar de .doc para .txt ?
			<ul>
				<li>Abra o arquivo no Word.</li>
				<li>Copie todo o conte&uacute;do do arquivo ( control + A , control + C ) </li>
				<li>Abra o bloco de notas e copie todo o conte&uacute;do copiado do Word.</li>
				<li>Salve o arquivo ( Control + S )</li>
			</ul>
		 </p> 
	 </div>
	<form method="post" action="./" enctype='multipart/form-data'>
		<label   style="display:table; margin:0 auto; border:1px solid #999; background:#eee; padding:20px; text-align:center;">
			<h2>Escolher arquivo</h2>
			<input type="file" name="arquivo" >
		</label>
		<input type="hidden" name="flag">
		<br>
		<input type="submit" onclick="document.getElementById('msg').innerHTML = 'Enviando arquivo...';" value="Enviar Arquivo" style='margin: 0 auto; padding: 20px 50px; margin-bottom:50px;'>
		<h3 id = 'msg'></h3>
	</form>
	<?php if (isset($_POST['flag'])){
	?>
		<h2><a href='download.php'>Download do Arquivo</a></h2>
	<?php
		foreach ($_FILES as $key => $FILE) {
			$nome = md5(date('h:i:s')).'.txt';
			$path= './uploads/';
			move_uploaded_file($FILE['tmp_name'], $path.$nome);
			chmod($path.$nome, 0777);

			$STRING_arquivo = file_get_contents($path.$nome);
			?>
			<div style="border: 1px solid gray; padding:15px; text-align:left;">
				<?php 
				// $string_tratada =  nl2br($STRING_arquivo);

				$string_tratada = preg_replace("/\r\n|\r|\n/",'+',$STRING_arquivo);


				// $string_tratada =  filter_var($string_tratada, FILTER_SANITIZE_STRING);




				$array_artigos = explode('+', $string_tratada);
				$array_final = array();
				$array_final['valores'] = array();
				$array_final['artigos'] = array();
				$array_final['rejeitados'] = array();

				$i=0;
				foreach ($array_artigos as $key => $artigo) {
					

					$artigo_tratado = str_replace(' ', '', strtolower($artigo)) ;
					$artigo_tratado = str_replace('.', '', $artigo_tratado) ;
					$artigo_tratado = str_replace(',', '', $artigo_tratado) ;
					$artigo_tratado = str_replace(')', '', $artigo_tratado) ;
					$artigo_tratado = str_replace('Â', '', $artigo_tratado) ;
					$artigo_tratado = str_replace('?', '', $artigo_tratado) ;
					$artigo_tratado = str_replace(':', '', $artigo_tratado) ;
					$artigo_tratado = str_replace('(', '', $artigo_tratado) ;
										$artigo_tratado =  slug($artigo_tratado);

					// $artigo_tratado = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($artigo_tratado));

					if (!in_array($artigo_tratado, $array_final['valores'])) {
						$array_final['valores'][]  = $artigo_tratado;
						$array_final['artigos'][]  = $artigo;
					} else {
						$array_final['rejeitados'][] = $artigo;
					}


				}

				?>
				<h2>
					Todos = <?php echo sizeof($array_artigos) ?>
				</h2>
				<h1 style="color : green;">
					Filtrados = <?php echo sizeof($array_final['valores']) ?>
				</h1>

				<?php  
					asort($array_final['rejeitados']);

					$string_final = "";
					foreach ($array_final['rejeitados'] as $key => $artigo_final) {
						$string_final .= "$artigo_final \r\n";  
					}
					echo '<kbd>'.nl2br($string_final).'</kbd>';

					file_put_contents('./artigos_tratados/saida.txt', $string_final);

				?>
			</div>
			<?php

		}
	}		
	?>

</body>

</html> 
