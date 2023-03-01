@extends('shop::public.layouts.basic')
@section('content')
<header class="top">
    <div class="container">
        <div class="logo-box">
            <div class="logo-image">
                <img src="/vendor/elfcms/shop/images/mid_logo_05.png" alt="">
            </div>
            <div class="logo-title">
                Art Shop
            </div>
        </div>
        <div class="top-nav-box">
            <div class="search-box">
                <form action="/" method="get">
                    <div class="search-field">
                        <input type="text" name="q">
                        <button type="submit"></button>
                    </div>
                </form>
            </div>
            <nav>
                <ul>
                    <li><a href="#" class="account-button"></a></li>
                    <li><a href="#" class="favorites-button"></a></li>
                    <li><a href="#" class="cart-button"></a></li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="category-nav-box">
            <nav>
                <ul>
                    <li>
                        <a href="#">Category 1</a>
                        <ul>
                            <li><a href="#">Subcategory 1.1</a></li>
                            <li><a href="#">Subcategory 1.2</a></li>
                            <li><a href="#">Subcategory 1.3</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Category 2</a></li>
                    <li>
                        <a href="#">Category 3</a>
                        <ul>
                            <li><a href="#">Subcategory 3.1</a></li>
                            <li><a href="#">Subcategory 3.2</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Category 4</a>
                        <ul>
                            <li><a href="#">Subcategory 4.1</a></li>
                            <li><a href="#">Subcategory 4.2</a></li>
                            <li><a href="#">Subcategory 4.3</a></li>
                            <li><a href="#">Subcategory 4.4</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Category 5</a>
                        <ul>
                            <li><a href="#">Subcategory 5.1</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<main>
    <div class="container">
        <h1>Products</h1>
        <div class="catalog-box">
            <aside class="filter-box">
                <h4>Filter</h4>
                <div class="filter-item">
                    <div class="filter-title">Price</div>
                    <div class="filter-content">
                        <input type="number" name="price_from" id="price_from" class="filter-price">
                        -
                        <input type="number" name="price_to" id="price_to" class="filter-price">
                        €
                    </div>
                </div>
            </aside>
            <section class="products-box">
                <div class="product-list">
                    <div class="product-item-box">
                        <a href="#" class="product-item">
                            <div class="product-item-image">
                                <img src="/vendor/elfcms/shop/images/products/paint-brush-5702384_500.png" alt="" data-color="1">
                                <img src="/vendor/elfcms/shop/images/products/paints-1946640_500.png" alt="" data-color="2">
                            </div>
                            <div class="product-item-title">
                                Product name
                            </div>
                            <div class="product-item-prices">
                                <div class="product-item-price old" data-currency="€">19,99</div>
                                <div class="product-item-new-price" data-currency="€">9,99</div>
                            </div>
                        </a>
                        <div class="product-item-colors">
                            <div class="product-item-color" style="background-color:#f39f5b;" data-color="1"></div>
                            <div class="product-item-color" style="background-color:#ffffff;" data-color="2"></div>
                        </div>
                    </div>
                    <div class="product-item-box">
                        <a href="#" class="product-item">
                            <div class="product-item-image">
                                <img src="/vendor/elfcms/shop/images/products/brushes-4226688_500.jpg" alt="">
                            </div>
                            <div class="product-item-title">
                                Product name
                            </div>
                            <div class="product-item-prices">
                                <div class="product-item-price" data-currency="€">7,99</div>
                            </div>
                        </a>
                    </div>
                    @foreach ($products as $product)
                    <div class="product-item-box">
                        <a href="#" class="product-item">
                            <div class="product-item-image">
                                {{-- <img src="{{ $product->items[0]->image ?? '' }}" alt="" data-color="2"> --}}
                            @foreach ($product->items as $item)
                                <img src="{{ $product->items[0]->image ?? '' }}" alt="" data-item="{{$item->id}}">
                            @endforeach
                            </div>
                            <div class="product-item-title">
                                {{ $product->title }}
                            </div>
                            <div class="product-item-prices">
                                <div class="product-item-price old" data-currency="€">19,99</div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="page-navigation"></div>
            </section>
        </div>
    </div>
</main>
{{-- <component-products :products="{{$products}}"></component-products> --}}
<component-products :product="1"></component-products>
<component-ttt></component-ttt>
<footer>
    <div class="container"></div>
</footer>
@endsection
