import {GenericAPI} from "./ServiceAPIs.js";
import {apiRoutes} from "@/router/api-routes.js";
import {useRequest} from "@/composables/api-request.js";

export const StaticPageAPI = Object.assign(
  GenericAPI(apiRoutes.admin.staticPages, {replacement: 'static_page'}),
  {
    // extra functionality goes here
  }
)

export const ContactAPI = Object.assign(
  GenericAPI(apiRoutes.admin.contacts, {
    except: ['store'],
    replacement: 'contact',
  }),
  {
    // extra functionality goes here
  }
)

export const ComplaintAPI = Object.assign(
  GenericAPI(apiRoutes.admin.complaints, {
    except: ['store'],
    replacement: 'complaint',
  }),
  {
    // extra functionality goes here
  }
)

export const FaqAPI = Object.assign(
  GenericAPI(apiRoutes.admin.faqs, {replacement: 'faq'}),
  {
    // extra functionality goes here
  }
)

export const NewsletterAPI = Object.assign(
  GenericAPI(apiRoutes.admin.newsletters, {
    except: ['update'],
    replacement: 'newsletter',
  }),
  {
    // extra functionality goes here
  }
)

export const SmsLogAPI = {
  fetchAll(callbacks) {
    useRequest(apiRoutes.admin.smsLogs, null, callbacks)
  },
}
