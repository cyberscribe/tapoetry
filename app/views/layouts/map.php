<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Transatlantic Poetry on Air</title>
    <style type="text/css">
        html, body {
          height: 100%;
          margin: 0;
          padding: 0;
        }

        #map_canvas {
          height: 100%;
        }
        #map_overlay {
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            opacity: 0.8;
            z-index: 1;
            background-color: rgba(127,127,127,0.8);
        }       

        @media print {
          html, body {
            height: auto;
          }

          #map_canvas {
            height: 650px;
          }
        }
    </style>
    <link rel="stylesheet" href="/wp-content/themes/wp-bootstrap/library/css/bootstrap.css?ver=1.0" />
    <link rel="stylesheet" href="/wp-content/themes/ta-bootstrap/style.css?ver=1.0" />
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
  </head>
  <body>
  <?php $this->render_main_view(); ?>
  </body>
</html>
