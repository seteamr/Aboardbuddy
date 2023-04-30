import { environment } from 'src/environments/environment';
const BASEURL = environment.baseUrl;


export const endPoints = {
  users:{
    signup:`${BASEURL}/sign-up`,
    login:`${BASEURL}/student-login`,
    profile:`${BASEURL}/profile`,
    updateProfile:`${BASEURL}/profile_update`,
    postTrip:`${BASEURL}/post_trip`,
    getTrips:`${BASEURL}/post_trips`,
    selelrSignup:`${BASEURL}/seller_registration`,
    updateSellerProfile:`${BASEURL}/seller_update`,
    contactUs:`${BASEURL}/contact_us`,
    postPartyCarpool:`${BASEURL}/add_carpool`,
    partyCarpoolList:`${BASEURL}/carpools`,
    joinPartyCarPool:`${BASEURL}/join_carpool`,
    deletePartyCarPool:`${BASEURL}/carpool_delete`,
  },

  data:{
    countries:`${BASEURL}/countries`,
    cities:`${BASEURL}/cities`,
    universities:`${BASEURL}/universities`
  },

  buddies:{
    findBuddies:`${BASEURL}/find_buddies`,
  },

  chat:{
    sendRequest:`${BASEURL}/message_request`,
    getRequests:`${BASEURL}/message_request_list`,
    getContacts:`${BASEURL}/my_request_list`,
    acceptRejectReq:`${BASEURL}/approve_reject`,
    sendMessage:`${BASEURL}/send_message`,
    getMessages:`${BASEURL}/messages_details`,
    messageDetails:`${BASEURL}/messages_details`,
  },
  advertise:{
    postAd:`${BASEURL}/post_add`,
    getAds:`${BASEURL}/my_post`,
    addByCity:`${BASEURL}/post_by_city`,
    getAdDetails:`${BASEURL}/post_details`,
    reportAdAsSpam:`${BASEURL}/add_spam`,
    
  },
  home:`${BASEURL}/home`,

};
