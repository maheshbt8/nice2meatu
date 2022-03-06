<!-- Shop Page One content -->
<section class="pro-content">
    <div class="row justify-content-center">
                    <div class="col-12 col-lg-6">
                        <div class="pro-heading-title">
                            <h2> WELCOME TO NICE 2 MEAT U STORE        </h2>

                        </div>
                    </div>
                </div>
   <!--  <div class="container">
        <div class="top-bar">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="row align-items-center">
                        <div class="col-12 d-flex justify-content-between">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> -->
    <section id="swap" class="shop-content shop-topbar shop-one">
        <div class="container-fluid">
            <div class="products-area">
                @include(isset(getSetting()['card_style']) ?
                    'includes.cart.product_card_'.getSetting()['card_style'] : "includes.cart.product_card_style1")
                <div class="row main_home_all_products">
                    
                </div>
            </div>

        </div>
    </section>


   <!--  <div class="container">
        <div class="pagination justify-content-between ">


        </div>
    </div> -->

</section>

