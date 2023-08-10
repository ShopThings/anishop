export const userRoutes = {
    path: '/user',
    children: [
        {
            path: '',
            name: 'user.home',
            component: () => import('../views/user/PageHome.vue'),
        },
    ],
    meta: {layout: 'layout-user'},
}
