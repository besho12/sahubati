<template>
    <section class="blog-posts-wrap">
        <div class="container">
            <div class="blog-posts-header">
                <h5 class="section-title">{{ data.title }}</h5>

                <a :href="route('blog_posts.index')" class="view-all">
                    {{ $trans("storefront::blog.blog_posts.view_all") }}
                </a>
            </div>

            <div class="blog-posts">
                <blog-post-card
                    v-for="(blogPost, index) in data.blogPosts"
                    :key="index"
                    :blog-post="blogPost"
                ></blog-post-card>
            </div>
        </div>
    </section>
</template>

<script>
import { slickPrevArrow, slickNextArrow } from "../../functions";
import BlogPostCard from "../BlogPostCard.vue";

export default {
    components: {
        BlogPostCard,
    },

    props: {
        data: {
            type: Object,
            required: true,
        },
    },

    mounted() {
        this.initSlickSlider();
    },

    methods: {
        initSlickSlider() {
            $(".blog-posts").slick({
                rows: 0,
                dots: true,
                arrows: false,
                infinite: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                rtl: window.FleetCart.rtl,
                prevArrow: slickPrevArrow(),
                nextArrow: slickNextArrow(),
                autoplay: true,     
                autoplaySpeed: 1500,
                speed: 500,
                infinite: true,
                 
                responsive: [
                    {
                        breakpoint: 1700,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 1301,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 921,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 641,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                ],
            });
        },
    },
};
</script>
