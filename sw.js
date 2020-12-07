/*
 Copyright 2019 IndiaTests. All Rights Reserved.
 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at
 http://www.apache.org/licenses/LICENSE-2.0
 */

'use strict';

const rootPath = 'https://easycoachingapp.com/app/';
const appTitle = 'EasyCoaching';
const notifyIcon = `${rootPath}themes/default/assets/img/touch/app-icon192.png`;
const notifyBadge = `${rootPath}themes/default/assets/img/notification-badge.png`;

const staticAssets = [
	rootPath,
	rootPath + 'themes/default/assests/css/',
	rootPath + 'themes/default/assests/js/',
	rootPath + 'themes/default/assests/img/',
];

self.addEventListener ('install', async event => {
	const cache = await caches.open ('EasyCoaching-V1');
	cache.addAll (staticAssets);
});

self.addEventListener ('fetch', event => {
	const request = event.request;
	const url = new URL (request.url);

	if (url.origin == location.origin) {
		event.respondWith (cacheFirst(request));		
	} else {
		event.respondWith (networkFirst(request));		
	}	
});

async function cacheFirst (request) { 
	const cachedResponse = await caches.match (request);
	return cachedResponse || fetch (request);
}

async function networkFirst (request) {
	const cache = await caches.open ('EasyCoaching-V1-dynamic');
	try {
		const response = await fetch(request);
		cache.put (request, response.clone());
		return response;
	} catch (error) {
		const cachedResponse = await cache.match (request);
		return cachedResponse || await caches.match (rootPath + 'fallback.json');
	}
}

self.addEventListener('push', function(event) {
  console.log('[Service Worker] Push Received.');
  let data = event.data.json();
  console.log(`[Service Worker] Push had this data:`, data);
  const title = data.title;
  const options = {
    body: data.content,
    data: {
      click_url: (data.link!=='')? data.link : ''
    },
    icon: notifyIcon,
    badge: notifyBadge
  };

  event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function(event) {
  console.log('[Service Worker] Notification click received.',rootPath, event);
  event.notification.close();
  if(event.notification.data.click_url!==''){
  	event.waitUntil(clients.matchAll({ type: 'window' }).then(clientsArr => {
    // If a Window tab matching the targeted URL already exists, focus that;
    const hadWindowToFocus = clientsArr.some(windowClient => windowClient.url === event.notification.data.url ? (windowClient.focus(), true) : false);
    // Otherwise, open a new tab to the applicable URL and focus it.
    if (!hadWindowToFocus){
    	clients.openWindow(`${rootPath}${event.notification.data.click_url}`).then(windowClient => windowClient ? windowClient.focus() : null);
    }
	}));
  }
});
