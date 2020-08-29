<?php
/**
 * Meta
 * @package site-user-register
 * @version 0.0.1
 */

namespace SiteUserRegister\Library;


class Meta
{
	static function single(){
        $result = [
            'head' => [],
            'foot' => []
        ];

        $home_url = \Mim::$app->router->to('siteHome');

        $page = (object)[
            'title'         => 'Register',
            'description'   => 'Register page for non registered new user',
            'schema'        => 'WebSite',
            'keyword'       => '',
            'page'          => \Mim::$app->router->to('siteMeRegister')
        ];

        $result['head'] = [
            'description'       => $page->description,
            'schema.org'        => [],
            'type'              => 'article',
            'title'             => $page->title,
            'url'               => $page->page,
            'metas'             => []
        ];

        // schema breadcrumbList
        $result['head']['schema.org'][] = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $home_url,
                        'name' => \Mim::$app->config->name
                    ]
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'item' => [
                        '@id' => $home_url . '#auth',
                        'name' => 'Auth'
                    ]
                ]
            ]
        ];

        return $result;
    }
}