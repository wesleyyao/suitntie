import { prefix, fetchAccount } from './common.js';

$(document).ready(function(){
    fetchAccount();
    $('#oneOnOneContactDiv').load(`${prefix}/components/contact-form.html`);
});