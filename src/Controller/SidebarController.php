<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SidebarController extends AbstractController
{
    public function showAction(): Response
    {
        $menu_items = [];
        $userRole = $this->getUser()->getRoles();
        
        if (in_array('ROLE_ADMIN', $userRole)) {
            $menu_items[] = [
                'name' => 'Admin',
                'items' => [
                    [ 'name' => 'Users', 'path' => 'user_index',],
                ]
            ];
        }

        $menu_items[] = [
            'name' => 'Common routes',
            'items' => [
                [ 'name' => 'Common route 1', 'path' => 'common_route',],
            ]
        ];
        $menu_items[] = [
            'name' => 'Test link',
            'path' => 'test_route'
        ];

        return $this->render('menu.html.twig', [
            'menu_items' => $menu_items,
        ]);
    }
}
