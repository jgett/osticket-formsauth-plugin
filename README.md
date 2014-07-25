osticket-formsauth-plugin
=========================

Use ASP.NET FormsAuthentication to handle staff and user logins

Your FormsAuthentication cookie must be visible to osticket e.g. on the same domain. This depends on how you set up FormsAuthentication in your web.config file. The plugin needs to be able to read the cookie value which is passed to a controller as post data that looks like<br />
`cookieValue=<encrypted-cookie-value>`

You can code this controller any way you like but it needs to decrypt the cookie value and return a json object that looks like<br />
`{authenticated: <bool>, username: <string>, roles: [<string>, <string>, ...]}`<br />
If one of the roles is "staff" then the staff login will be allowed.

Users must be independently added/registered in osticket. The plugin does not add users to the system but will log them in if it finds a matching username. This is mainly because the authentication system might not be aware of all the osticket details like department, oranization, email, etc.
