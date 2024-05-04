const isProd = false;

const Config = {
        
    backendConfig : {
    apiUrl: 'http://localhost:8000',
},

    frontendConfig : {
    indexUrl : "http://localhost:5173/"
},

};

if (isProd) {
    const Config = {
        
        backendConfig : {
        apiUrl: 'https://ecommercebackend.xoop.website/public',
    },

        frontendConfig : {
        indexUrl : "https://ecommerce.xoop.website/"
    },

    };
}


export default Config;