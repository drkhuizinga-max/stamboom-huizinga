// server/api/_sitemap-urls.ts
import { serverQueryContent } from '#content/server'

export default defineEventHandler(async (e) => {
  const contentList = await serverQueryContent(e).find()
  return contentList.map(p => {
    return {
      loc: p._path,
      lastmod: p.updatedAt
    }
  })
})