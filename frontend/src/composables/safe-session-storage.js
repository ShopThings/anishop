import Crypto from 'crypto-js'
import Cookie from 'js-cookie'
import {v4} from 'uuid'

const cookieName = 'ensureSafeDataSession'
// Get the encryption token from cookie or generate a new one.
const encryptionToken = Cookie.get(cookieName) || v4()

// Store the encryption token in a secure cookie.
Cookie.set(cookieName, encryptionToken, {secure: true, expires: 180})

export const useSafeSessionStorage = {
    getItem: (key) => {
        // Get the store from local storage.
        const store = window.sessionStorage.getItem(key)

        if (store) {
            try {
                // Decrypt the store retrieved from local storage
                // using our encryption token stored in cookies.
                const bytes = Crypto.AES.decrypt(store, encryptionToken)

                return JSON.parse(bytes.toString(Crypto.enc.Utf8))
            } catch (e) {
                // The store will be reset if decryption fails.
                window.sessionStorage.removeItem(key)
            }
        }

        return null;
    },
    setItem: (key, value) => {
        // Encrypt the store using our encryption token stored in cookies.
        const store = Crypto.AES.encrypt(value, encryptionToken).toString()

        // Save the encrypted store in local storage.
        return window.sessionStorage.setItem(key, store)
    },
    removeItem: (key) => window.sessionStorage.removeItem(key),
}
