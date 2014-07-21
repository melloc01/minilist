<?php
	$diretorio = './artigos_tratados/';
	$arquivo = 'saida.txt';

	$arquivo = $diretorio.$arquivo;
	header('Content-type: octet/stream');
	header('Content-disposition: attachment; filename="'.basename($arquivo).'";');
	header('Content-Length: '.filesize($arquivo));
    	readfile($arquivo);
?>
