

self.addEventListener("install", e=>{

 e.waitUntil(
   caches.open("static").then(cache =>{
     return cache.addAll(["./","./manifest.json","./icons/apple-icon-152x152.png","./icons/icon-384x384.png"])
   })
 );

})


self.addEventListener("fetch", e =>{

   e.respondWith(
     caches.match(e.request).then(response =>{
       return response || fetch(e.request)
     })
   )
})
