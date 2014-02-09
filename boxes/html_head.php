<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Rigorix - Gioco online a premi</title>
  <meta name="description" content="Gioca gratis e vinci bellissimi premi ai rigori. Rigorix e' uno dei piu' divertenti giochi online gratuiti in flash in cui si vincono veri premi. Gioca online!">
  <meta name="keywords" content="giochi on line gratuiti a premi">
  <style type="text/css">
    @import "/app/assets/dist/app.css";
    /*@import "/css/developing.css";*/
  </style>
  <script>
    var User = <?php echo ( is_object($user->obj) ? FastJSON::convert( $user->obj ) : 'false' ); ?>;
    var RigorixEnv = <?php echo FastJSON::convert($env); ?>;
  </script>

  <script src="/app/assets/dist/app.js" type="text/javascript"></script>
</head>