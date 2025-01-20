import './bootstrap';

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js', {scope: '/'}).then(function (registration) {
        //
    }).catch(function (registrationError) {
        console.log('SW Registration Failed!');
    });
}
