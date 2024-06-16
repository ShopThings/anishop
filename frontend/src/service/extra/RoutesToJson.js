import fs from 'fs';
import path from 'path';

const CHANGE_FREQUENCY = {
  always: 'always',
  hourly: 'hourly',
  daily: 'daily',
  weekly: 'weekly',
  monthly: 'monthly',
  yearly: 'yearly',
  never: 'never',
}

export default () => {
  /**
   * ----------------------------------------
   * ----------- PLEASE READ THIS -----------
   * ----------------------------------------
   *
   * 📍After run this, you need to hand created json route file to backend people!
   *   [FYI: They know what they should do with it, just follow the structure]
   *
   * Route structure:
   * [
   *   [
   *     path: string,
   *     mapped_params: object (optional) (on dynamic routes),
   *     relations: object (optional) (on dynamic routes) map a parameter to another parameter also they must exist in "mapped_params", it'll resolve on backend,
   *     changefreq: string (optional) (use CHANGE_FREQUENCY constant),
   *     priority: float (optional),
   *   ],
   *   ...,
   * ]
   *
   */

  const filename = 'routes.json'
  const distDir = path.join('src', 'assets', 'temp')
  const dist = path.join(distDir, filename)

  const routes = [
    {
      path: '/home',
      changefreq: CHANGE_FREQUENCY.daily,
      priority: 1.0,
    },
    {
      path: '/login',
      changefreq: CHANGE_FREQUENCY.never,
      priority: 0.7,
    },
    {
      path: '/signup',
      changefreq: CHANGE_FREQUENCY.never,
      priority: 0.5,
    },
    {
      path: '/forget-password',
      changefreq: CHANGE_FREQUENCY.never,
      priority: 0.5,
    },
    {
      path: '/pages/:url([a-zA-Z]+[a-zA-Z\/\-]*[a-zA-Z]+)',
      mapped_params: {
        url: 'page',
      },
      changefreq: CHANGE_FREQUENCY.monthly,
      priority: 0.8,
    },
    {
      path: '/blog/search',
      changefreq: CHANGE_FREQUENCY.weekly,
      priority: 0.9,
    },
    {
      path: '/blog/:slug([^\\\/\.]+)',
      mapped_params: {
        slug: 'blog',
      },
      changefreq: CHANGE_FREQUENCY.monthly,
      priority: 0.9,
    },
    {
      path: '/search',
      changefreq: CHANGE_FREQUENCY.weekly,
      priority: 1.0,
    },
    {
      path: '/product/:slug([^\\\/\.]+)',
      mapped_params: {
        slug: 'product',
      },
      changefreq: CHANGE_FREQUENCY.weekly,
      priority: 1.0,
    },
    {
      path: '/brands',
      changefreq: CHANGE_FREQUENCY.weekly,
      priority: 0.9,
    },
    {
      path: '/faq',
      changefreq: CHANGE_FREQUENCY.weekly,
      priority: 1.0,
    },
    {
      path: '/contact',
      changefreq: CHANGE_FREQUENCY.monthly,
      priority: 0.9,
    },
    {
      path: '/cart',
      changefreq: CHANGE_FREQUENCY.never,
      priority: 0.5,
    },
  ]

  const json = JSON.stringify(routes)

  fs.mkdir(distDir, {recursive: true}, (err) => {
    if (err) {
      console.error(err)
      return
    }

    fs.writeFile(dist, json, (err) => {
      if (err) {
        console.error(err)
      } else {
        console.log('JSON route file created successfully!')
      }
    })
  })
}
