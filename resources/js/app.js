import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
// we switch channel to private cuz it's public
var channel = Echo.private(`App.Models.User.${user_id}`);
channel.notification(function(data) {
    console.log(data);
    alert(data.body);
});
