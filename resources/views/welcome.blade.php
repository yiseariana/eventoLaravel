<!doctype html> <html> <head> <meta charset="utf-8"> <title></title> <meta name="description" content=""> <meta name="viewport" content="width=device-width"> <!-- Place favicon.ico and apple-touch-icon.png in the root directory --> <link rel="stylesheet" href="styles/vendor.dc1bb9bf.css"> <link rel="stylesheet" href="styles/main.5366d9a4.css"> <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"> </head> <body ng-app="cooapicaApp" data-spy="scroll" data-offset="0" data-target="#navbar-main"> <div ng-controller="MainCtrl as main"> <div ui-view="nav"></div> <div ui-view="content"></div> </div> <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1TYOFgAEMsSGI4TNLb9h8eoQpTVHXvJk&signed_in=true&callback=initMap"></script> <script src="scripts/vendor.db3625bd.js"></script> <script src="scripts/scripts.9f5c503a.js"></script> <script type="text/javascript">$(document).ready(function () {
                                                    var f1 = function () {
                                                        var fulls = $('.header');
                                                        var win = $(window);
                                                        fulls.height(win.height());
                                                    };
                                                    $(window).bind('resize', f1);
                                                    f1();
                                                });</script> <script type="text/javascript">jQuery(function ($) {
                $('.navbar-nav > li').click(function (event) {
                    event.preventDefault();
                    var target = $(this).find('>a').prop('hash');
                    $('html, body').animate({
                        scrollTop: $(target).offset().top
                    }, 600);
                });
                //scrollspy
                $('[data-spy="scroll"]').each(function () {
                    var $spy = $(this).scrollspy('refresh')
                });
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    var target = $(e.target).attr("href");
                    if (target === '#sede') {
                        console.log("Map");
                        initMap();
                    }
                });

                google.maps.event.addDomListener(window, 'load', initMap);
            });</script> </body> </html>