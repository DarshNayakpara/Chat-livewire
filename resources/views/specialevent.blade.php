<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
     @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])
     @livewireStyles
</head>
<body>
     <h1> Hello World </h1>
     <script>
          window.onload = function(){
               console.log('hello world');
               Echo.channel('orders')
               .listen('OrderShipped', (e) => {
                    console.log(1);
                    console.log(e.msg);
                    console.log(2);
               });
          }
          </script>   
          @livewireScripts
</body>
</html>