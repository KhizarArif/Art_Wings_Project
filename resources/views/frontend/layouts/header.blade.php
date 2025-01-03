<?php

use function App\Helpers\getSubCategories;

$subCategories = getSubCategories();
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/imageGallary.css') }}" />


    {{-- Toaster --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />


    <title>Art Wings</title>


    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB -->

    <!-- jQuery -->



    <!-- Styles -->
    <style>
        /* Color of the links BEFORE scroll */
        .navbar-before-scroll .nav-link,
        .navbar-before-scroll .navbar-toggler-icon {
            color: #f3d2d2;
        }

        /* Color of the links AFTER scroll */
        .navbar-after-scroll .nav-link,
        .navbar-after-scroll .navbar-toggler-icon {
            color: #fff;
        }

        /* Color of the navbar AFTER scroll */
        .navbar-after-scroll {
            background-color: #000;
        }

        /* Transition after scrolling */
        .navbar-after-scroll {
            transition: background 0.5s ease-in-out, padding 0.5s ease-in-out;
        }

        /* Transition to the initial state */
        .navbar-before-scroll {
            transition: background 0.5s ease-in-out, padding 0.5s ease-in-out;
        }

        /* An optional height of the navbar AFTER scroll */
        .navbar.navbar-before-scroll.navbar-after-scroll {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        /* Navbar on mobile */
        @media (max-width: 991.98px) {

            .navbar-before-scroll .nav-link,
            .navbar-before-scroll .navbar-toggler-icon {
                color: #f71a1a;
            }

            /* #main-navbar {
                background-color: #131212;
            }

            .nav-link,
            .navbar-toggler-icon {
                color: #ffffff !important;
            } */
        }
    </style>
</head>

<body>
    <!--Main Navigation-->
    <header style="height: 15vh; background-color: #131212">

        <!-- Navbar -->
        <nav id="main-navbar" class="navbar navbar-expand-md fixed-top navbar-before-scroll shadow-0 ">
            <!-- Container wrapper -->
            <div class="container-fluid">
                <!-- Toggle button -->
                <button data-mdb-collapse-init data-mdb-button-init class="navbar-toggler bg-primary " type="button"
                    data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="margin-top: 1rem;">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="sidebar" style="display: none">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-6 mt-5 p-0">
                            <div class="logo">
                                <a href="{{ route('home') }}" class="d-flex justify-content-center  m-3 ">
                                    <img src="{{ asset('frontend_assets/img/logo.png') }}" alt="logo" loading="lazy"
                                        height="100%" width="100%" />
                                </a>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <ul class="social_media_container ">
                                <li class="nav-item cart_count_container">
                                    <a class="nav-link pe-2" href="{{ route('front.cart') }}" title="Add to cart">
                                        <i class="fas fa-shopping-cart fa-lg"></i>
                                        <div class="cart_count">{{ Cart::count() }}</div>
                                    </a>
                                </li>
                                <div class="d-flex flex-wrap mt-3">
                                    <li class="nav-item">
                                        <a class="nav-link pe-2" href="#!">
                                            <i class="fab fa-youtube fa-2x"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link px-2" href="#!">
                                            <i class="fab fa-facebook-f fa-2x"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link px-2" href="#!">
                                            <i class="fab fa-twitter fa-2x"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link ps-2" href="#!">
                                            <i class="fab fa-instagram fa-2x"></i>
                                        </a>
                                    </li>
                                </div>
                            </ul>
                        </div>

                    </div>

                    <div class="category_mobile" >

                        @if ($subCategories->isNotEmpty())
                            <ul class="navbar_subCategory">
                                @foreach ($subCategories as $getSubCategory)
                                    <a href="javascript:void(0)" class="navbar_subCategory_link">
                                        <li class="navbar_subCategory_text">{{ $getSubCategory->name }}</li>
                                    </a>
                                @endforeach
                            </ul>
                        @endif
                    </div>


                </div>



                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Collapsible wrapper -->

                    <div class="navbar_container">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="logo">
                                    <a href="{{ route('home') }}" class="text-align-center m-1 ">
                                        <img src="{{ asset('frontend_assets/img/logo.png') }}" alt="logo" loading="lazy"
                                            height="30%" width="60%" />
                                    </a>
                                </div>
                            </div>

                            {{-- Categories Container Start --}}
                            <div class="col-md-8" style="margin: auto;">
                                <div class="navbar_categories">

                                    @if ($subCategories->isNotEmpty())
                                        <ul class="navbar_subCategory">
                                            @foreach ($subCategories as $getSubCategory)
                                                <a href="javascript:void(0)" class="navbar_subCategory_link">
                                                    <li class="navbar_subCategory_text">{{ $getSubCategory->name }}</li>
                                                </a>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>

                            </div>
                            {{-- Categories Container End --}}

                            {{-- Social Container Start --}}
                            <div class="col-md-2" style="margin: auto;">
                                <ul class="social_media_container ">
                                    <li class="nav-item cart_count_container">
                                        <a class="nav-link pe-2" href="{{ route('front.cart') }}" title="Add to cart">
                                            <i class="fas fa-shopping-cart"></i>
                                            <div class="cart_count">{{ Cart::count() }}</div>
                                        </a>
                                    </li>
                                    <div class="d-flex">
                                        <li class="nav-item">
                                            <a class="nav-link pe-2" href="#!">
                                                <i class="fab fa-youtube"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-2" href="#!">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-2" href="#!">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link ps-2" href="#!">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                        </li>
                                    </div>
                                </ul>
                            </div>
                            {{-- Social Container End --}}
                        </div>

                    </div>
                </div>

            </div>
            <!-- Container wrapper -->
        </nav>
        <!-- Navbar -->



    </header>
    <!--Main Navigation-->
