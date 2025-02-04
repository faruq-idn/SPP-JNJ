import './bootstrap';
import zxcvbn from 'zxcvbn';

// Tambahkan script global disini

// Fungsi untuk mengecek apakah cookies diizinkan
function areCookiesEnabled() {
    try {
        document.cookie = "cookietest=1";
        var ret = document.cookie.indexOf("cookietest=") !== -1;
        document.cookie = "cookietest=1; samesite=lax";
        var ret = document.cookie.indexOf("cookietest=") !== -1;
        document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT; samesite=lax";
        return ret;
    } catch (e) {
        return false;
    }
}

// Tampilkan peringatan jika cookies diblokir
if (!areCookiesEnabled()) {
    console.warn('Cookies dibutuhkan untuk fungsi optimal aplikasi');
}





