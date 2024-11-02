<?php

namespace Modules\Storefront\Http\ViewComposers;

use Illuminate\View\View;
use Modules\Storefront\Banner;
use Modules\Storefront\Feature;
use Modules\Brand\Entities\Brand;
use Illuminate\Support\Collection;
use Modules\Blog\Entities\BlogPost;
use Modules\Slider\Entities\Slider;
use Illuminate\Support\Facades\Cache;
use Modules\Category\Entities\Category;

class HomePageComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose($view)
    {
        $view->with([
            'slider' => Slider::findWithSlides(setting('storefront_slider')),
            'sliderBanners' => Banner::getSliderBanners(),
            'features' => Feature::all(),
            'featuredCategories' => $this->featuredCategoriesSection(),
            'threeColumnFullWidthBanners' => $this->threeColumnFullWidthBanners(),
            'productTabsOne' => $this->productTabsOne(),
            'topBrands' => $this->topBrands(),
            'flashSaleAndVerticalProducts' => $this->flashSaleAndVerticalProducts(),
            'twoColumnBanners' => $this->twoColumnBanners(),
            'twoColumnBanners1' => $this->twoColumnBanners1(),
            'twoColumnBanners2' => $this->twoColumnBanners2(),
            'twoColumnBanners3' => $this->twoColumnBanners3(),
            'twoColumnBanners4' => $this->twoColumnBanners4(),
            'productGrid' => $this->productGrid(),
            'threeColumnBanners' => $this->threeColumnBanners(),
            'tabProductsTwo' => $this->tabProductsTwo(),
            'oneColumnBanner' => $this->oneColumnBanner(),
            'oneColumnBanner1' => $this->oneColumnBanner1(),
            'oneColumnBanner2' => $this->oneColumnBanner2(),
            'oneColumnBanner3' => $this->oneColumnBanner3(),
            'oneColumnBanner4' => $this->oneColumnBanner4(),
            'oneColumnBanner5' => $this->oneColumnBanner5(),
            'blogPosts' => $this->blogPosts(),
        ]);
    }


    private function featuredCategoriesSection()
    {
        if (!setting('storefront_featured_categories_section_enabled')) {
            return;
        }

        return [
            'title' => setting('storefront_featured_categories_section_title'),
            'subtitle' => setting('storefront_featured_categories_section_subtitle'),
            'categories' => $this->getFeaturedCategories(),
        ];
    }


    private function getFeaturedCategories()
    {
        $categoryIds = Collection::times(6, function ($number) {
            if (!is_null(setting("storefront_featured_categories_section_category_{$number}_product_type"))) {
                return setting("storefront_featured_categories_section_category_{$number}_category_id");
            }
        })->filter();

        return Category::with('files')
            ->whereIn('id', $categoryIds)
            ->when($categoryIds->isNotEmpty(), function ($query) use ($categoryIds) {
                $query->orderByRaw("FIELD(id, {$categoryIds->filter()->implode(',')})");
            })
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'logo' => $category->logo,
                ];
            });
    }


    private function threeColumnFullWidthBanners()
    {
        if (setting('storefront_three_column_full_width_banners_enabled')) {
            return Banner::getThreeColumnFullWidthBanners();
        }
    }


    private function productTabsOne()
    {
        if (!setting('storefront_product_tabs_1_section_enabled')) {
            return;
        }

        return Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_1_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_1_section_tab_{$number}_title");
            }
        })->filter();
    }


    private function topBrands()
    {
        if (!setting('storefront_top_brands_section_enabled')) {
            return collect();
        }

        $topBrandIds = setting('storefront_top_brands', []);

        return Cache::rememberForever(md5('storefront_top_brands:' . serialize($topBrandIds)), function () use ($topBrandIds) {
            return Brand::with('files')
                ->whereIn('id', $topBrandIds)
                ->when(!empty($topBrandIds), function ($query) use ($topBrandIds) {
                    $topBrandIdsString = collect($topBrandIds)->filter()->implode(',');

                    $query->orderByRaw("FIELD(id, {$topBrandIdsString})");
                })
                ->get()
                ->map(function (Brand $brand) {
                    return [
                        'url' => $brand->url(),
                        'logo' => $brand->getLogoAttribute(),
                    ];
                });
        });
    }


    private function flashSaleAndVerticalProducts()
    {
        return [
            'flash_sale_title' => setting('storefront_flash_sale_title'),
            'vertical_products_1_title' => setting('storefront_vertical_products_1_title'),
            'vertical_products_2_title' => setting('storefront_vertical_products_2_title'),
            'vertical_products_3_title' => setting('storefront_vertical_products_3_title'),
        ];
    }


    private function twoColumnBanners()
    {
        if (setting('storefront_two_column_banners_enabled')) {
            return Banner::getTwoColumnBanners();
        }
    }

    private function twoColumnBanners1()
    {
        if (setting('storefront_two_column_banners1_enabled')) {
            return Banner::getTwoColumnBanners1();
        }
    }

    private function twoColumnBanners2()
    {
        if (setting('storefront_two_column_banners2_enabled')) {
            return Banner::getTwoColumnBanners2();
        }
    }

    private function twoColumnBanners3()
    {
        if (setting('storefront_two_column_banners3_enabled')) {
            return Banner::getTwoColumnBanners3();
        }
    }

    private function twoColumnBanners4()
    {
        if (setting('storefront_two_column_banners4_enabled')) {
            return Banner::getTwoColumnBanners4();
        }
    }


    private function productGrid()
    {
        if (!setting('storefront_product_grid_section_enabled')) {
            return;
        }

        return Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_grid_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_grid_section_tab_{$number}_title");
            }
        })->filter();
    }


    private function threeColumnBanners()
    {
        if (setting('storefront_three_column_banners_enabled')) {
            return Banner::getThreeColumnBanners();
        }
    }


    private function tabProductsTwo()
    {
        if (!setting('storefront_product_tabs_2_section_enabled')) {
            return;
        }

        $tabs = Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_2_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_2_section_tab_{$number}_title");
            }
        })->filter();

        return [
            'title' => setting('storefront_product_tabs_2_section_title'),
            'tabs' => $tabs,
        ];
    }


    private function oneColumnBanner()
    {
        if (setting('storefront_one_column_banner_enabled')) {
            return Banner::getOneColumnBanner();
        }
    }

    private function oneColumnBanner1()
    {
        if (setting('storefront_one_column_banner1_enabled')) {
            return Banner::getOneColumnBanner1();
        }
    }

    private function oneColumnBanner5()
    {
        if (setting('storefront_one_column_banner5_enabled')) {
            return Banner::getOneColumnBanner5();
        }
    }

    private function oneColumnBanner2()
    {
        if (setting('storefront_one_column_banner2_enabled')) {
            return Banner::getOneColumnBanner2();
        }
    }

    private function oneColumnBanner3()
    {
        if (setting('storefront_one_column_banner3_enabled')) {
            return Banner::getOneColumnBanner3();
        }
    }

    private function oneColumnBanner4()
    {
        if (setting('storefront_one_column_banner4_enabled')) {
            return Banner::getOneColumnBanner4();
        }
    }


    private function blogPosts()
    {
        if (setting('storefront_blogs_section_enabled')) {
            $blogPosts = BlogPost::published()
                ->latest()
                ->take(setting('storefront_recent_blogs') ?? 10)
                ->get();

            foreach ($blogPosts as $blogPost) {
                $blogPost->append('user_name');
            }

            return [
                'title' => setting('storefront_blogs_section_title'),
                'blogPosts' => $blogPosts,
            ];
        }
    }
}
