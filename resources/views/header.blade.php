<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="user" content="{{Auth::user()}}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script type="text/javascript">
          @auth
             window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
          @else
             window.Permissions = [];
          @endauth
       </script>
        <title>Lambda</title>
        <link rel="shortcut icon" type="image/png" href="{{ asset('/img/favicon.ico') }}">
        <link rel="shortcut icon" sizes="192x192" href="{{ asset('/img/favicon.ico') }}">
        <link rel="stylesheet" href="{{ asset('css/lte.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fontisto@v3.0.4/css/fontisto/fontisto.min.css"></i>
        
    </head>
    <style>
     
        .principal {
          float: none;
          margin: 0 auto;
        }
        .icon-a {
          color: #FFF;
          font-size: 0.9em;
      }
        </style>
