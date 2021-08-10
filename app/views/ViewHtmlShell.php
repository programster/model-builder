<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewHtmlShell extends \Programster\AbstractView\AbstractView
{
    private string|Stringable $m_content;
    
    
    public function __construct(string|Stringable $content)
    {
        $this->m_content = $content;
    }
    
    
    protected function renderContent() 
    {
?>
 <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>title</title>
        <meta name="author" content="name">
        <meta name="description" content="description here">
        <meta name="keywords" content="keywords,here">
        
        <!-- Bootstrap core CSS -->
        <link href="/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
        
        <link rel="stylesheet" href="/css/style.css" type="text/css">

        <!-- VueJS -->
        <script 
          src="https://cdn.jsdelivr.net/npm/vue@2.6.14"
          integrity="sha384-ULpZhk1pvhc/UK5ktA9kwb2guy9ovNSTyxPNHANnA35YjBQgdwI+AhLkixDvdlw4"
          crossorigin="anonymous"
        >
        </script>
        

    </head>
    <body>
        <?= $this->m_content; ?>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
        <script src="/bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    </body>
</html>


<?php
    }

}
