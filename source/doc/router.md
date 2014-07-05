# Routers
We use 2 different routers to root our calls to the right place. 

The main router comes from the slim framework and handles the main app call AND THE restful REQUESTS. He shows the app, the expired, browser info or error page and deligates the language, cache, assets, share, optin and ajax requests to the right actions.
- [read more about the slim router](#link_to_the_slim_router_page)

The second router is from backbone. He deligates all app internal requests from JS the the right files, to show them into the content or to start a new RESTFUL request. You can find the backbone router part in the URL after the # character. All thinks before are the slim part.
- [read more about the backbone router](#link_to_the_backbone_router_page)