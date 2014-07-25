namespace Sample.Controllers
{
    public class AuthCheckController : Controller
    {
		public ActionResult AuthCheck(string cookieValue = "")
        {
            Client c = null;

            if (!string.IsNullOrEmpty(cookieValue))
            {
                var ticket = FormsAuthentication.Decrypt(cookieValue);

                if (!string.IsNullOrEmpty(ticket.Name))
                {
					//use whatever data access method you have here...
                    c = DA.Search<Client>(x => x.UserName == ticket.Name).FirstOrDefault();
                    if (c == null)
                        return Json(new { success = false, message = string.Format("no client found for '{0}'", ticket.Name) });

					//in my case a Client object has the method Roles which returns a string array. If the client is staff then one
					//of the elements will be the string "staff", but you can create this array in whatever way makes sense
						
                    //success!
                    return Json(new { success = true, message = "", authenticated = !ticket.Expired, username = ticket.Name, roles = c.Roles(), lastName = c.LName, firstName = c.FName, email = c.PrimaryEmail(), expiration = ticket.Expiration, expired = ticket.Expired });
				}

                return Json(new { success = false, message = "no username found", authenticated = false, username = "" });
            }
			else
				return Json(new { success = false, message = "missing cookieValue", authenticated = false , username = "" });
        }