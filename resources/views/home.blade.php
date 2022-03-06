@extends('layouts.master')
@section('content')

    @include('sections.home-products-section')

    @foreach (homePageBuilderJson() as $template)
        @if (!$template['skip'] && $template['display'])
            @include('sections.home-'.$template['template_postfix'].'-section')
        @endif
    @endforeach


@endsection
@section('script')
    <script>
        $(document).ready(function() {

            var url = "{{ url('') }}" +
                '/api/client/products?limit=10&getCategory=1&getDetail=1&language_id=' + languageId +
                '&topSelling=1&currency=' + localStorage.getItem("currency");
            appendTo = 'tab_top_sales';
            fetchProduct(url, appendTo);

            var url = "{{ url('') }}" + '/api/client/products?limit=10&getDetail=1&language_id=' +
                languageId + '&currency=' + localStorage.getItem("currency");
            appendTo = 'tab_special_products';
            fetchProduct(url, appendTo);

            var url = "{{ url('') }}" + '/api/client/products?limit=10&getDetail=1&language_id=' +
                languageId + '&currency=' + localStorage.getItem("currency");
            appendTo = 'tab_most_liked';
            fetchProduct(url, appendTo);

            var url = "{{ url('') }}" +
                '/api/client/products?limit=12&getCategory=1&getDetail=1&language_id=' + languageId +
                '&sortBy=id&sortType=DESC&currency=' + localStorage.getItem("currency");
            appendTo = 'new-arrival';
            fetchProduct(url, appendTo);


            var url = "{{ url('') }}" +
                '/api/client/products?limit=6&getCategory=1&getDetail=1&language_id=' + languageId +
                '&sortBy=id&sortType=DESC&currency=' + localStorage.getItem("currency");
            appendTo = 'weekly-sale';
            fetchProduct(url, appendTo);

            var url = "{{ url('') }}" +
                '/api/client/products?limit=1&getCategory=1&getDetail=1&language_id=' + languageId +
                '&topSelling=1&currency=' + localStorage.getItem("currency");
            appendTo = 'weekly-sale-first-div';
            fetchFeaturedWeeklyProduct(url,appendTo)

            blogNews();
            sliderMedia();
            categorySlider();
            bannerMedia();
            cartSession = $.trim(localStorage.getItem("cartSession"));
            if (cartSession == null || cartSession == 'null') {
                cartSession = '';
            }
            menuCart(cartSession);
        });


        function fetchProduct(url, appendTo) {
            $.ajax({
                type: 'get',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.status == 'Success') {
                        const templ = document.getElementById("product-card-template");

                        for (i = 0; i < data.data.length; i++) {
                            const clone = templ.content.cloneNode(true);
                            // clone.querySelector(".single-text-chat-li").classList.add("bg-blue-100");
                            
                            clone.querySelector(".wishlist-icon").setAttribute('data-id', data.data[i]
                                .product_id);
                            clone.querySelector(".wishlist-icon").setAttribute('onclick', 'addWishlist(this)');
                            
                            clone.querySelector(".wishlist-icon").setAttribute('data-type', data.data[i]
                                .product_type);

                            clone.querySelector(".wishlist-icon-2").setAttribute('data-id', data.data[i]
                                .product_id);
                            clone.querySelector(".wishlist-icon-2").setAttribute('onclick', 'addWishlist(this)');
                            
                            clone.querySelector(".wishlist-icon-2").setAttribute('data-type', data.data[i]
                                .product_type);
                                
                            clone.querySelector(".compare-icon").setAttribute('data-id', data.data[i]
                                .product_id);
                            clone.querySelector(".compare-icon").setAttribute('data-type', data.data[i]
                                .product_type);
                            clone.querySelector(".compare-icon").setAttribute('onclick', 'addCompare(this)');
                            clone.querySelector(".quick-view-icon").setAttribute('data-id', data.data[i]
                                .product_id);
                            clone.querySelector(".quick-view-icon").setAttribute('data-type', data.data[i]
                                .product_type);
                            clone.querySelector(".quick-view-icon").setAttribute('onclick',
                                'quiclViewData(this)');


                            clone.querySelector(".quantity-right-plus").setAttribute('data-field', i);
                            clone.querySelector(".quantity-left-minus").setAttribute('data-field', i);
                            clone.querySelector(".qty-input").setAttribute('id', 'quantity'+i);
                            clone.querySelector(".item-quantity").classList.add('itemqty'+i);

                            

                            var bages = '';
                            if(data.data[i].discount_percentage > 0)
                                bages +='<span class="badge badge-danger">'+data.data[i].discount_percentage+'%</span>';
                            if(data.data[i].is_featured != "0")
                                bages +='<span class="badge badge-success">Featured</span>';
                            if(data.data[i].new != "0")
                                bages +='<span class="badge badge-info ">New</span>';
                            
                            clone.querySelector(".badges").innerHTML = bages;

                            
                            rating = '';
                            if(data.data[i].product_rating == 1){
                                rating = '<label class="full fa " for="star1" title="Awesome - 1 stars"></label><label class="full fa " for="star_2" title="Awesome - 2 stars"></label><label class="full fa " for="star_3" title="Awesome - 3 stars"></label><label class="full fa " for="star_4" title="Awesome - 4 stars"></label><label class="full fa active" for="star_5" title="Awesome - 5 stars"></label>'
                            }
                            else if(data.data[i].product_rating == 2){
                                rating = '<label class="full fa " for="star1" title="Awesome - 1 stars"></label><label class="full fa " for="star_2" title="Awesome - 2 stars"></label><label class="full fa " for="star_3" title="Awesome - 3 stars"></label><label class="full fa active" for="star_4" title="Awesome - 4 stars"></label><label class="full fa active" for="star_5" title="Awesome - 5 stars"></label>'
                            }
                            else if(data.data[i].product_rating == 3){
                                rating = '<label class="full fa " for="star1" title="Awesome - 1 stars"></label><label class="full fa " for="star_2" title="Awesome - 2 stars"></label><label class="full fa active" for="star_3" title="Awesome - 3 stars"></label><label class="full fa active" for="star_4" title="Awesome - 4 stars"></label><label class="full fa active" for="star_5" title="Awesome - 5 stars"></label>'
                            }
                            else if(data.data[i].product_rating == 4){
                                rating = '<label class="full fa " for="star1" title="Awesome - 1 stars"></label><label class="full fa active" for="star_2" title="Awesome - 2 stars"></label><label class="full fa active" for="star_3" title="Awesome - 3 stars"></label><label class="full fa active" for="star_4" title="Awesome - 4 stars"></label><label class="full fa active" for="star_5" title="Awesome - 5 stars"></label>'
                            }
                            else if(data.data[i].product_rating == 5){
                                rating = '<label class="full fa active" for="star1" title="Awesome - 1 stars"></label><label class="full fa active" for="star_2" title="Awesome - 2 stars"></label><label class="full fa active" for="star_3" title="Awesome - 3 stars"></label><label class="full fa active" for="star_4" title="Awesome - 4 stars"></label><label class="full fa active" for="star_5" title="Awesome - 5 stars"></label>'
                            }
                            else{
                                rating = '<label class="full fa " for="star1" title="Awesome - 1 stars"></label><label class="full fa " for="star_2" title="Awesome - 2 stars"></label><label class="full fa " for="star_3" title="Awesome - 3 stars"></label><label class="full fa " for="star_4" title="Awesome - 4 stars"></label><label class="full fa " for="star_5" title="Awesome - 5 stars"></label>'
                            }
                            
                            clone.querySelector(".display-rating").innerHTML = rating;
                            clone.querySelector(".display-rating1").innerHTML = rating;

                            if (data.data[i].product_gallary != null && data.data[i].product_gallary !=
                                'null' && data.data[i].product_gallary != '') {
                                if (data.data[i].product_gallary.detail != null && data.data[i].product_gallary
                                    .detail != 'null' && data.data[i].product_gallary.detail != '') {
                                    clone.querySelector(".product-card-image").setAttribute('src', data.data[i]
                                        .product_gallary.detail[1].gallary_path);
                                }
                            }
                            if (data.data[i].detail != null && data.data[i].detail != 'null' && data.data[i]
                                .detail != '') {
                                clone.querySelector(".product-card-image").setAttribute('alt', data.data[i]
                                    .detail[0].title);
                            }
                            if (data.data[i].category != null && data.data[i].category != 'null' && data.data[i]
                                .category != '') {
                                if (data.data[i].category[0].category_detail != null && data.data[i].category[0]
                                    .category_detail != 'null' && data.data[i].category[0].category_detail != ''
                                ) {
                                    if (data.data[i].category[0].category_detail.detail != null && data.data[i]
                                        .category[0].category_detail.detail != 'null' && data.data[i].category[
                                            0].category_detail.detail != '') {
                                        clone.querySelector(".product-card-category").innerHTML = data.data[i]
                                            .category[0].category_detail.detail[0].name;
                                    }
                                }
                            }
                            if (data.data[i].detail != null && data.data[i].detail != 'null' && data.data[i]
                                .detail != '') {
                                clone.querySelector(".product-card-name").innerHTML = data.data[i].detail[0]
                                    .title;
                                clone.querySelector(".product-card-name").setAttribute('href', '/product/' +
                                    data
                                    .data[i].product_id + '/' + data
                                    .data[i].product_slug);
                                var desc = data.data[i].detail[0].desc;
                                clone.querySelector(".product-card-desc").innerHTML = desc.substring(0, 50);
                            }

                            if (data.data[i].product_type == 'simple') {
                                if (data.data[i].product_discount_price == '' || data.data[i]
                                    .product_discount_price == null || data.data[i].product_discount_price ==
                                    'null') {
                                    clone.querySelector(".product-card-price").innerHTML = data.data[i]
                                        .product_price_symbol;
                                } else {
                                    clone.querySelector(".product-card-price").innerHTML =
                                    data.data[i]
                                        .product_discount_price_symbol + '<span>' +data.data[i].product_price_symbol + '</span>';
                                }
                            } else {
                                console.log(data.data[i].product_variable_price_symbol,"variable price");
                                    clone.querySelector(".product-card-price").innerHTML = data.data[i].product_variable_price_symbol;
                            }



                            if (data.data[i].product_type == 'simple') {
                                clone.querySelector(".product-card-link").setAttribute('onclick',
                                    "addToCart(this)");
                                clone.querySelector(".product-card-link").setAttribute('data-id', data.data[i]
                                    .product_id);
                                clone.querySelector(".product-card-link").setAttribute('data-field', i);
                                clone.querySelector(".product-card-link").setAttribute('data-type', data.data[i]
                                    .product_type);
                                clone.querySelector(".product-card-link").innerHTML = 'Add To Cart';

                                clone.querySelector(".add-to-card-bag").setAttribute('onclick', "addToCart(this)");
                                clone.querySelector(".add-to-card-bag").setAttribute('data-id', data.data[i].product_id);
                                clone.querySelector(".add-to-card-bag").setAttribute('data-type', data.data[i].product_type);
                                clone.querySelector(".add-to-card-bag").setAttribute('data-field', i);

                            } else {
                                // $().addClass();
                                clone.querySelector('.itemqty'+i).classList.add('d-none');
                                clone.querySelector(".add-to-card-bag").classList.add('d-none');
                                clone.querySelector(".product-card-link").classList.remove('d-g-none');
                                clone.querySelector(".product-card-link").classList.remove('listing-none');
                                clone.querySelector(".product-card-link").innerHTML = 'View Detail';   
                                clone.querySelector(".product-card-link").setAttribute('href', '/product/' +
                                    data
                                    .data[i].product_id + '/' + data
                                    .data[i].product_slug);
                            }

                            $("." + appendTo).append(clone);
                            
                            if (appendTo == 'new-arrival' || appendTo == 'weekly-sale') {
                                $(".div-class").addClass('col-12 col-sm-6 col-lg-3');
                            }
                        }


                        if (appendTo != 'new-arrival' && appendTo != 'weekly-sale')
                            getSliderSettings(appendTo);
                    }
                },
                error: function(data) {},
            });
        }


        function fetchFeaturedWeeklyProduct(url, appendTo) {
            $.ajax({
                type: 'get',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.status == 'Success') {
                        console.log(data,"final data");
                        var htmlToRender ="<article><div class='badges'><span class='badge badge-success'>Featured</span></div><div class='detail'>";
                        
                            htmlToRender +='<h5 class="title"><a  href="/product/'+data
                                    .data[0].product_id +'/'+data
                                    .data[0].product_slug+'">'+data.data[0].detail[0]
                                    .title+'</a></h5>';


                            htmlToRender +='<p class="discription">'+data.data[0].detail[0]
                                    .desc+'</p>';
                            
                            
                            

                            if (data.data[0].product_type == 'simple') {
                                if (data.data[0].product_discount_price == '' || data.data[0]
                                    .product_discount_price == null || data.data[0].product_discount_price ==
                                    'null') {
                                    htmlToRender +='<div class="price">'+data.data[0]
                                        .product_price_symbol+'</div>';
                                } else {
                                    htmlToRender +='<div class="price">'+data.data[0]
                                        .product_discount_price_symbol + '<span>' +data.data[0].product_price_symbol + '</span></div>';
                                }
                            } else {
                                if (data.data[0].product_combination != null && data.data[0]
                                    .product_combination != 'null' && data.data[0].product_combination != '') {
                                        htmlToRender +='<div class="price">'+data.data[0]
                                        .product_combination[0].product_price_symbol+'</div>';
                                }
                            }

                            htmlToRender +='<div class="pro-sub-buttons"><div class="buttons"><button type="button" class="btn  btn-link " data-id='+data.data[0]
                                .product_id+' onclick="addWishlist(this)" data-type='+data.data[0]
                                .product_type+'><i class="fas fa-heart"></i>Add to Wishlist</button>';
                                
                            htmlToRender +='<button type="button" class="btn btn-link" data-id='+data.data[0]
                                .product_id+' data-type='+data.data[0]
                                .product_type+' onclick="addCompare(this)" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add to Compare"><i class="fas fa-align-right"></i>Add to Compare</button></div></div></div>';
                            htmlToRender +='<picture><div class="product-hover">';
                            if (data.data[0].product_type == 'simple') {
                                htmlToRender +='<button type="button" data-id="'+data.data[0].product_id+'" data-field="'+0+'" data-type="'+data.data[0].product_type+'" onclick="addToCart(this)" class="btn btn-block btn-secondary cart swipe-to-top" >Add to Cart</button>';

                            } else {
                                
                                htmlToRender +='<a href="/product/'+data
                                    .data[0].product_id +'/'+data
                                    .data[0].product_slug+'" onclick="addToCart(this)" class="btn btn-block btn-secondary cart swipe-to-top" >View Detail</a>';
        
                            }
                            
                            htmlToRender +='</div>';

                             if (data.data[0].product_gallary != null && data.data[0].product_gallary !=
                                'null' && data.data[0].product_gallary != '') {
                                if (data.data[0].product_gallary.detail != null && data.data[0].product_gallary
                                    .detail != 'null' && data.data[0].product_gallary.detail != '') {
                                       htmlToRender +='<img class="img-fluid" src="'+data.data[0]
                                        .product_gallary.detail[1].gallary_path+'" alt="Men"s Cotton Classic Baseball Cap">';

                                }
                            }
                            htmlToRender +='</picture></article>';
                           

                        $('#weekly-sale-first-div').html(htmlToRender);
                    }
                },
                error: function(data) {},
            });
        }

        function blogNews() {
            $.ajax({
                type: 'get',
                url: "{{ url('') }}" +
                    '/api/client/blog_news?getGallaryDetail=1&limit=10&sortBy=id&language_id=' + languageId +
                    '&getDetail=1&getBlogCategory=1&sortType=DESC',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.status == 'Success') {
                        $(".blog-news-data").html('');
                        const templ = document.getElementById("news-blog-template");
                        // clone.querySelector(".single-text-chat-li").classList.add("bg-blue-100");
                        for (i = 0; i < data.data.length; i++) {
                            const clone = templ.content.cloneNode(true);
                            // clone.querySelector(".single-text-chat-li").classList.add("bg-blue-100");
                            clone.querySelector(".news-blog-date").innerHTML = data.data[i].date;
                            clone.querySelector(".news-blog-date").setAttribute('data-id', data.data[i]
                                .product_id);
                            clone.querySelector(".blog-url").setAttribute('href', '/blog-detail/' + data.data[i]
                                .slug);
                            clone.querySelector(".read-more-url").setAttribute('href', '/blog-detail/' + data
                                .data[i].slug);

                            if (data.data[i].gallary != null && data.data[i].gallary != 'null' && data.data[i]
                                .gallary != '') {
                                if (data.data[i].gallary.detail != null && $.trim(data.data[i].gallary
                                        .detail) != '' && data.data[i].gallary.detail != 'null') {
                                    if (data.data[i].gallary.detail[2].gallary_path) {
                                        clone.querySelector(".news-blog-image").setAttribute('src', data.data[i]
                                            .gallary.detail[1].gallary_path);
                                    } else {
                                        clone.querySelector(".news-blog-image").setAttribute('src', data.data[i]
                                            .gallary.detail[0].gallary_path);
                                    }
                                }
                            }
                            if (data.data[i].detail != null && $.trim(data.data[i].detail) != '' && data.data[i]
                                .detail != 'null') {
                                clone.querySelector(".news-blog-image").setAttribute('alt', data.data[i].detail[
                                    0].name);
                            }
                            if (data.data[i].category != null && data.data[i].category != 'null' && $.trim(data
                                    .data[i].category) != '') {
                                if (data.data[i].category.blog_detail != null && data.data[i].category
                                    .blog_detail != 'null' && data.data[i].category.blog_detail != '') {
                                    clone.querySelector(".news-blog-category").innerHTML = data.data[i].category
                                        .blog_detail[0].name;
                                }
                            }
                            if (data.data[i].detail != null && data.data[i].detail != 'null' && $.trim(data
                                    .data[i].detail) != '') {
                                clone.querySelector(".news-blog-name").innerHTML = data.data[i].detail[0].name;
                                clone.querySelector(".news-blog-desc").innerHTML = data.data[i].detail[0]
                                    .description;
                            }
                            $(".blog-news-data").append(clone);
                        }
                        getSliderSettings("blog-news-data");
                    }
                },
                error: function(data) {},
            });
        }



        function sliderMedia() {
            var sliderType = "{{ getSetting()['slider_style'] ? getSetting()['slider_style'] : '' }}";
            if (sliderType == "style1") {
                sliderType = 1;
            }
            if (sliderType == "style2") {
                sliderType = 2;
            }
            if (sliderType == "style3") {
                sliderType = 3;
            }
            if (sliderType == "style4") {
                sliderType = 4;
            }
            if (sliderType == "style5") {
                sliderType = 5;
            }
            $.ajax({
                type: 'get',
                url: "{{ url('') }}" +
                    '/api/client/slider?getLanguage=' + languageId +
                    '&getSliderType=1&getSliderNavigation=1&getSliderGallary=1&limit=5&sortBy=id&sortType=ASC&sliderType=' +
                    sliderType + '&language_id=' + languageId,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.status == 'Success') {
                        $(".slider-navigation-show").html('');
                        const templ = document.getElementById("slider-navigation-template");
                        // clone.querySelector(".single-text-chat-li").classList.add("bg-blue-100");
                        for (i = 0; i < data.data.length; i++) {


                            $("#slider-bullets-" + i).addClass("d-block");
                            $("#slider-bullets-" + i).removeClass('d-none')

                            const clone = templ.content.cloneNode(true);
                            // clone.querySelector(".single-text-chat-li").classList.add("bg-blue-100");
                            clone.querySelector(".slider-navigation-title").innerHTML = data.data[i]
                                .slider_title;
                            clone.querySelector(".slider-navigation-desc").innerHTML = data.data[i]
                                .slider_description;
                            clone.querySelector(".slider-navigation-url").setAttribute('href', data.data[i]
                                .slider_url);

                            clone.querySelector(".carousel-caption").classList.add(data.data[i]
                                .slider_position);
                            clone.querySelector(".carousel-caption").classList.add(data.data[i]
                                .slider_textcontent);
                            clone.querySelector(".carousel-caption").classList.add(data.data[i]
                                .slider_text);
                                
                            if (i == 0) {
                                clone.querySelector(".slider-navigation-active").classList.add("active");
                            }
                            if (data.data[i].gallary != null && $.trim(data.data[i].gallary) != '') {
                                clone.querySelector(".slider-navigation-image").setAttribute('src',
                                    '/gallary/' + data.data[i].gallary);
                            }
                            $(".slider-navigation-show").append(clone);
                        }
                    }
                },
                error: function(data) {},
            });


            $.ajax({
                type: 'get',
                url: "{{ url('') }}" +
                    '/api/client/constant_banner?getLanguage=' + languageId +
                    '&title=rightsliderbanner&language_id=' + languageId + '&getGallary=1',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.status == 'Success') {
                        var side_banners = '';
                        side_banners += '<figure class="banner-image imagespace">';
                        side_banners +=
                            '<a class="banner-slider-link1" href=""><img class="img-fluid banner-slider-image1" src="" alt="Banner Image"></a>';
                        side_banners += '</figure>';
                        side_banners += '<figure class="banner-image ">';
                        side_banners +=
                            '<a class="banner-slider-link2" href=""><img class="img-fluid banner-slider-image2" src="" alt="Banner Image"></a>';
                        side_banners += '</figure>';
                        $('.side-banners').html(side_banners);

                        $('.banner-slider-link1').attr('href', "{{ url('') }}" + data.data[0]
                            .banner_url);
                        $('.banner-slider-image1').attr('src', "/gallary/" + data.data[0].gallary.gallary_name);



                        $('.banner-slider-link2').attr('href', "{{ url('') }}" + data.data[1]
                            .banner_url);
                        $('.banner-slider-image2').attr('src', "/gallary/" + data.data[1].gallary.gallary_name);


                    }
                },
                error: function(data) {},
            });
        }


        function categorySlider() {
            $.ajax({
                type: 'get',
                url: "{{ url('') }}" +
                    '/api/client/category?getDetail=1&page=1&limit=10&getGallary=1&language_id=' + languageId +
                    '&sortBy=category_name&sortType=DESC',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.status == 'Success') {
                        $(".category-slider-show").html('');
                        const templ = document.getElementById("category-slider-template");
                        // clone.querySelector(".single-text-chat-li").classList.add("bg-blue-100");
                        for (i = 0; i < data.data.length; i++) {
                            const clone = templ.content.cloneNode(true);
                            // clone.querySelector(".single-text-chat-li").classList.add("bg-blue-100");
                            clone.querySelector(".category-slider-url").setAttribute('href', '/shop?category=' +
                                data.data[i].id);
                            clone.querySelector(".category-slider-image").setAttribute('src', '/gallary/' + data
                                .data[i].icon);
                            clone.querySelector(".category-slider-title").innerHTML = data.data[i].name;
                            $(".category-slider-show").append(clone);
                        }
                        getSliderSettings("category-slider-show");
                    }
                },
                error: function(data) {},
            });
        }



        function bannerMedia() {
            var bannerType = "{{ getSetting()['banner_style'] ? getSetting()['banner_style'] : 'style1' }}";
            if (bannerType == "style1") {
                bannerType = 'banner1';
            }
            if (bannerType == "style2" || bannerType == "style3" || bannerType == "style4") {
                bannerType = "banner2";
            }
            if (bannerType == "style5" || bannerType == "style6") {
                bannerType = "banner5";
            }
            if (bannerType == "style7" || bannerType == "style8") {
                bannerType = "banner7";
            }
            if (bannerType == "style9") {
                bannerType = "banner9";
            }
            if (bannerType == "style10" || bannerType == "style11" || bannerType == "style12") {
                bannerType = "banner10";
            }

            if (bannerType == "style13" || bannerType == "style14" || bannerType == "style15") {
                bannerType = "banner13";
            }

            if (bannerType == "style16" || bannerType == "style17") {
                bannerType = "banner16";
            }

            if (bannerType == "style18" || bannerType == "style19") {
                bannerType = "banner18";
            }
            $('.banner_div').css('display', 'none');
            $.ajax({
                type: 'get',
                url: "{{ url('') }}" + '/api/client/constant_banner?getLanguage=' + languageId + '&title=' +
                    bannerType +
                    '&language_id=' + languageId + '&getGallary=1',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.status == 'Success') {
                        if (typeof data.data[0] !== 'undefined') {
                            $('.banner-link1').attr('href', data.data[0]
                                .banner_url);

                            $('.banner-image1').attr('src', "/gallary/" + data.data[0].gallary
                                .gallary_name);
                        }



                        if (typeof data.data[1] !== 'undefined') {
                            $('.banner-link2').attr('href', data.data[1]
                                .banner_url);

                            $('.banner-image2').attr('src', "/gallary/" + data.data[1].gallary
                                .gallary_name);
                        }




                        if (typeof data.data[2] !== 'undefined') {
                            $('.banner-link3').attr('href', data.data[2]
                                .banner_url);
                            $('.banner-image3').attr('src', "/gallary/" + data.data[2].gallary
                                .gallary_name);
                        }

                        if (typeof data.data[3] !== 'undefined') {
                            $('.banner-link4').attr('href', data.data[3]
                                .banner_url);
                            $('.banner-image4').attr('src', "/gallary/" + data.data[3].gallary
                                .gallary_name);
                        }

                        if (typeof data.data[4] !== 'undefined') {

                            $('.banner-link5').attr('href', data.data[4]
                                .banner_url);
                            $('.banner-image5').attr('src', "/gallary/" + data.data[4].gallary
                                .gallary_name);
                        }
                        if (typeof data.data[5] !== 'undefined') {
                            $('.banner-link6').attr('href', data.data[5]
                                .banner_url);
                            $('.banner-image6').attr('src', "/gallary/" + data.data[5].gallary
                                .gallary_name);

                        }
                        $('.banner_div').css('display', 'block');
                    }
                },
                error: function(data) {},
            });
        }


        $(document).on('click', '.quantity-right-plus', function() {
            var row_id = $(this).attr('data-field');

            var quantity = $('#quantity' + row_id).val();
            $('#quantity' + row_id).val(parseInt(quantity) + 1);
        })

        $(document).on('click', '.quantity-left-minus', function() {
            var row_id = $(this).attr('data-field');
            var quantity = $('#quantity' + row_id).val();
            if (quantity > 1)
                $('#quantity' + row_id).val(parseInt(quantity) - 1);
        })
    </script>

    <script>
        var language_id = localStorage.getItem('languageId');
        var attribute_id = [];
        var attribute = [];
        var variation_id = [];
        var variation = [];
        var sortBy = "";
        var sortType = "";
        var priceFromSidebar = "{{ isset($_GET['price']) ? $_GET['price'] : '' }}";
        var shopStyle = "{{ getSetting()['shop'] }}";
        $(document).ready(function() {
            fetchProduct_home(1);
            $(".variaion-filter").each(function() {
                if ($(this).val() != "") {
                    attribute_id.push($(this).attr('data-attribute-id'));
                    variation_id.push($(this).val());
                    attribute.push($(this).attr('data-attribute-name'));
                    variation.push($('option:selected', this).attr('data-variation-name'));
                }

            });
        });

        function fetchProduct_home(page) {
            var limit = "{{ isset($_GET['limit']) ? $_GET['limit'] : '12' }}";
            var category = "{{ isset($_GET['category']) ? $_GET['category'] : '' }}";
            var varations = "{{ isset($_GET['variation_id']) ? $_GET['variation_id'] : '' }}";
            var price_range = "{{ isset($_GET['price']) ? $_GET['price'] : '' }}";

            var url = "{{ url('') }}" + '/api/client/products?page=' + page + '&limit=' + limit +
                '&getDetail=1&language_id=' + language_id + '&currency=' + localStorage.getItem("currency");

            if (category != "")
                url += "&productCategories=" + category;
            if (varations != "")
                url += "&variations=" + varations;
            if (price_range != "") {
                price_range = price_range.split("-");
                url += "&price_from=" + price_range[0];
                url += "&price_to=" + price_range[1];
            }

            if (sortBy != "" && sortType != "")
                url += "&sortBy=" + sortBy + "&sortType=" + sortType;
            var searchinput = "{{ isset($_GET['search']) ? $_GET['search'] : '' }}";
            if (searchinput != "")
                url += "&searchParameter=" + searchinput;
            var appendTo = 'main_home_all_products';
            $.ajax({
                type: 'get',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    clientid: "{{ isset(getSetting()['client_id']) ? getSetting()['client_id'] : '' }}",
                    clientsecret: "{{ isset(getSetting()['client_secret']) ? getSetting()['client_secret'] : '' }}",
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.status == 'Success') {
                        if (data.meta.last_page < page) {
                            $('.load-more-products').attr('disabled', true);
                            $('.load-more-products').html('No More Items');
                            return
                        }
                        var pagination =
                            '<label for="staticEmail" class="col-form-label">Showing From <span class="showing_record">' +
                            data.meta.to + '</span>&nbsp;of&nbsp;<span class="showing_total_record">' + data
                            .meta.total + '</span>&nbsp;results.</label>';
                        var nextPage = parseInt(data.meta.current_page) + 1;
                        pagination += '<div class="col-12 col-sm-6">';
                        pagination += '<ol class="loader-page mt-0">';
                        pagination += '<li class="loader-page-item">';
                        pagination += '<button class="load-more-products btn btn-secondary" data-page="' +
                            nextPage + '">Load More</button>';
                        pagination += '</li>';
                        pagination += '</ol>';
                        pagination += '</div>';

                        $('.pagination').html(pagination);
                        const templ = document.getElementById("product-card-template");
                        for (i = 0; i < data.data.length; i++) {
                            const clone = templ.content.cloneNode(true);
                            // clone.querySelector(".single-text-chat-li").classList.add("bg-blue-100");
                            clone.querySelector(".div-class").classList.add('col-12');
                            if (shopStyle.split('style')[1] == 1)
                                clone.querySelector(".div-class").classList.add('col-lg-3');
                            else
                                clone.querySelector(".div-class").classList.add('col-lg-4');
                            clone.querySelector(".div-class").classList.add('col-md-6');
                            clone.querySelector(".div-class").classList.add('griding');
                            clone.querySelector(".wishlist-icon").setAttribute('data-id', data.data[i]
                                .product_id);
                            clone.querySelector(".wishlist-icon").setAttribute('data-type', data.data[i]
                                .product_type);
                            clone.querySelector(".wishlist-icon").setAttribute('onclick', 'addWishlist(this)');

                            clone.querySelector(".wishlist-icon-2").setAttribute('data-id', data.data[i]
                                .product_id);
                            clone.querySelector(".wishlist-icon-2").setAttribute('data-type', data.data[i]
                                .product_type);
                            clone.querySelector(".wishlist-icon-2").setAttribute('onclick',
                            'addWishlist(this)');

                            clone.querySelector(".compare-icon").setAttribute('data-id', data.data[i]
                                .product_id);
                            clone.querySelector(".compare-icon").setAttribute('data-type', data.data[i]
                                .product_type);
                            clone.querySelector(".quick-view-icon").setAttribute('data-id', data.data[i]
                                .product_id);
                            clone.querySelector(".compare-icon").setAttribute('onclick', 'addCompare(this)');
                            clone.querySelector(".quick-view-icon").setAttribute('onclick',
                                'quiclViewData(this)');

                            clone.querySelector(".quantity-right-plus").setAttribute('data-field', i);
                            clone.querySelector(".quantity-left-minus").setAttribute('data-field', i);
                            clone.querySelector(".qty-input").setAttribute('id', 'quantity_r'+i);
                            clone.querySelector(".item-quantity").classList.add('itemqty'+i);

                            if (data.data[i].product_gallary != null) {
                                if (data.data[i].product_gallary.detail != null) {
                                    clone.querySelector(".product-card-image").setAttribute('src', data.data[i]
                                        .product_gallary.detail[0].gallary_path);
                                }
                            }
                            if (data.data[i].detail != null) {
                                clone.querySelector(".product-card-image").setAttribute('alt', data.data[i]
                                    .detail[0].title);
                            }
                            if (data.data[i].category != null) {
                                if (data.data[i].category[0].category_detail.detail != null) {
                                    clone.querySelector(".product-card-category").innerHTML = data.data[i]
                                        .category[0].category_detail.detail[0].name;
                                }
                            }
                            if (data.data[i].detail != null) {
                                clone.querySelector(".product-card-name").innerHTML = data.data[i].detail[0]
                                    .title;
                                clone.querySelector(".product-card-name").setAttribute('href', '/product/' +
                                    data
                                    .data[i].product_id + '/' + data
                                    .data[i].product_slug);
                                var desc = data.data[i].detail[0].desc;
                                clone.querySelector(".product-card-desc").innerHTML = desc.substring(0, 50);
                            }

                            if (data.data[i].product_type == 'simple') {
                                if (data.data[i].product_discount_price == '' || data.data[i]
                                    .product_discount_price == null || data.data[i].product_discount_price ==
                                    'null') {
                                    clone.querySelector(".product-card-price").innerHTML = data.data[i]
                                        .product_price_symbol;
                                } else {

                                    clone.querySelector(".product-card-price").innerHTML = data.data[i]
                                        .product_discount_price_symbol + '<span>' + data.data[i]
                                        .product_price_symbol + '</span>';
                                }
                            }  else {
                                console.log(data.data[i].product_variable_price_symbol,"variable price");
                                    clone.querySelector(".product-card-price").innerHTML = data.data[i].product_variable_price_symbol;
                            }

                            var bages = '';
                            if(data.data[i].discount_percentage > 0)
                                bages +='<span class="badge badge-danger">'+data.data[i].discount_percentage+'%</span>';
                            if(data.data[i].is_featured != "0")
                                bages +='<span class="badge badge-success">Featured</span>';
                            if(data.data[i].new != "0")
                                bages +='<span class="badge badge-info ">New</span>';
                            
                            clone.querySelector(".badges").innerHTML = bages;

                            if (data.data[i].product_type == 'simple') {
                                clone.querySelector(".product-card-link").setAttribute('onclick',
                                    "addToCart(this)");
                                clone.querySelector(".product-card-link").setAttribute('data-id', data.data[i]
                                    .product_id);
                                clone.querySelector(".product-card-link").setAttribute('data-type', data.data[i]
                                    .product_type);
                                clone.querySelector(".product-card-link").innerHTML = 'Add To Cart';
                                clone.querySelector(".product-card-link").setAttribute('data-field', i);

                                clone.querySelector(".add-to-card-bag").setAttribute('onclick',
                                    "addToCart(this)");
                                clone.querySelector(".add-to-card-bag").setAttribute('data-id', data.data[i]
                                    .product_id);
                                clone.querySelector(".add-to-card-bag").setAttribute('data-type', data.data[i]
                                    .product_type);
                                clone.querySelector(".add-to-card-bag").setAttribute('data-field', i);


                            } else {
                                clone.querySelector('.itemqty'+i).classList.add('d-none');
                                clone.querySelector(".add-to-card-bag").classList.add('d-none');
                                clone.querySelector(".product-card-link").classList.remove('d-g-none');
                                clone.querySelector(".product-card-link").classList.remove('listing-none');
                                clone.querySelector(".product-card-link").innerHTML = 'View Detail';
                                clone.querySelector(".product-card-link").setAttribute('href', '/product/' +
                                    data.data[i].product_id + '/' + data.data[i].product_slug);
                            }

                            $("." + appendTo).append(clone);
                        }
                    }
                },
                error: function(data) {},
            });
        }


        var limit = "{{ isset($_GET['limit']) ? $_GET['limit'] : '12' }}";
        var shopRedirecturl = "{{ url('/shop') }}" + '?limit=' + limit;
        $('.category-filter').change(function() {
            $(this).attr('selected', true);
        })
        $('.price-filter').change(function() {
            $(this).attr('selected', true);
        })

        $('.variaion-filter').on('change', function() {

            if (attribute_id.indexOf($(this).attr('data-attribute-id')) === -1) {
                attribute_id.push($(this).attr('data-attribute-id'));
                variation_id.push($(this).val());
                attribute.push($(this).attr('data-attribute-name'));
                variation.push($('option:selected', this).attr('data-variation-name'));
            } else {

                var index = attribute_id.indexOf($(this).attr('data-attribute-id'));
                if ($(this).val() == "") {
                    attribute_id.splice(index, 1);
                    variation_id.splice(index, 1);
                    attribute.splice(index, 1);
                    variation.splice(index, 1);
                } else {
                    attribute_id[index] = $(this).attr('data-attribute-id');
                    variation_id[index] = $(this).val();
                    attribute[index] = $(this).attr('data-attribute-name');
                    variation[index] = $('option:selected', this).attr('data-variation-name');
                }

            }


        })

        $('.price-range-list').on('click', function() {
            var price_range = $(this).attr('data-price-range');
            $('.price-range-list').each(function() {
                $('.price-range-list').removeClass("price-active");
            })
            $('.price-range-list' + '-' + price_range).addClass("price-active");
            priceFromSidebar = price_range;
        });

        $('.variation_list_item').on('click', function() {
            var variation_name = $(this).attr('data-variation-name');
            var attribute_name = $(this).attr('data-attribute-name').split(' ').join('_');

            $('.attribute_' + attribute_name + '_div').each(function() {
                $('.attribute_' + attribute_name + '_div').removeClass("variation_active");
            })

            $('.' + variation_name + '-' + attribute_name).addClass("variation_active");

            if (attribute_id.indexOf($(this).attr('data-attribute-id')) === -1) {
                attribute_id.push($(this).attr('data-attribute-id'));
                attribute.push($(this).attr('data-attribute-name'));
                variation_id.push($(this).attr('data-variation-id'));
                variation.push($(this).attr('data-variation-name'));

            } else {

                var index = attribute_id.indexOf($(this).attr('data-attribute-id'));
                if ($(this).attr('data-variation-id') == "") {
                    attribute_id.splice(index, 1);
                    variation_id.splice(index, 1);
                    attribute.splice(index, 1);
                    variation.splice(index, 1);
                } else {
                    attribute_id[index] = $(this).attr('data-attribute-id');
                    variation_id[index] = $(this).attr('data-variation-id');
                    attribute[index] = $(this).attr('data-attribute-name');
                    variation[index] = $(this).attr('data-variation-name');
                }

            }

            console.log(attribute_id, variation_id, attribute, variation)
        })

        $('#filter').click(function(e) {
            e.preventDefault();

            filter();
        })

        $('.filter-from-sidebar').click(function() {
            filter();
        })

        function filter() {
            var limit = "{{ isset($_GET['limit']) ? $_GET['limit'] : '12' }}";
            var searchinput = "{{ isset($_GET['search']) ? $_GET['search'] : '' }}";

            if ($('.category-filter').val() != "" && $('.category-filter').val() != undefined) {
                shopRedirecturl += "&category=" + $('.category-filter').val();
            }
            if ($('.price-filter').val() != "" && $('.price-filter').val() != undefined) {
                shopRedirecturl += "&price=" + $('.price-filter').val();
            } else if (priceFromSidebar != "") {
                shopRedirecturl += "&price=" + priceFromSidebar;
            }

            if (searchinput != "")
                shopRedirecturl += "&searchParameter=" + searchinput;
            if (variation_id.length > 0)
                shopRedirecturl += "&attribute=" + attribute;
            if (variation_id.length > 0)
                shopRedirecturl += "&variation=" + variation;
            if (variation_id.length > 0)
                shopRedirecturl += "&attribute_id=" + attribute_id;
            if (variation_id.length > 0)
                shopRedirecturl += "&variation_id=" + variation_id;
            window.location.href = shopRedirecturl;
        }

        $(document).on('click', '.load-more-products', function() {
            var pageToLoad = $(this).attr('data-page');
            fetchProduct(pageToLoad);
        })

        $(document).on('click', '.quantity-right-plus', function() {
            var row_id = $(this).attr('data-field');

            var quantity = $('#quantity_r' + row_id).val();
            $('#quantity_r' + row_id).val(parseInt(quantity) + 1);
        })

        $(document).on('click', '.quantity-left-minus', function() {
            var row_id = $(this).attr('data-field');
            var quantity = $('#quantity_r' + row_id).val();
            if (quantity > 1)
                $('#quantity_r' + row_id).val(parseInt(quantity) - 1);
        })
    </script>
@endsection
