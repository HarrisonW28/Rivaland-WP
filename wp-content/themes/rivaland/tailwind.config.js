let fontSizes = {};
let spacing = {};
for (let i = 0; i < 200; i++) {
    fontSizes[i] = i + "px";
    spacing[i] = i + "px";
}


module.exports = {
    content: require('fast-glob').sync([
      './*.php',
      './includes/blocks/*.php',
      './includes/lib/*.php',
      './includes/partials/*.php',
      './js/*.js',
      './includes/blocks/**/*.php',
      './template-parts/*.php',
      './template-parts/*/*.php',
      './templates/*.php',
      './components/*.php',
    ]),
    theme: {
        screens: {
            'xsm': '360px',
            // => @media (min-width: 360px) { ... }

            'sm': '500px',
            // => @media (min-width: 500px) { ... }
        
            'md': '830px',
            // => @media (min-width: 830px) { ... }

            'lg': '1050px',
            // => @media (min-width: 1050px) { ... }
        
            'xl': '1280px',
            // => @media (min-width: 1280px) { ... }
        
            '2xl': '1440px',
            // => @media (min-width: 1440px) { ... }

            '3xl': '1921px',
            // => @media (min-width: 1921px) { ... }
        },
        spacing: spacing,
        fontSize: fontSizes,
        lineHeight: {
            "0.1": "0.1",
            "0.2": "0.2",
            "0.3": "0.3",
            "0.4": "0.4",
            "0.5": "0.5",
            "0.6": "0.6",
            "0.7": "0.7",
            "0.8": "0.8",
            "0.9": "0.9",
            "1": "1",
            "1.1": "1.1",
            "1.2": "1.2",
            "1.3": "1.3",
            "1.4": "1.4",
            "1.5": "1.5",
            "1.6": "1.6",
            "1.7": "1.7",
            "1.8": "1.8",
            "1.9": "1.9",
            "2.0": "2.0"
        },
        extend: { 
            fontFamily: {
                sans: ['Roboto', 'sans-serif'],
            },
            colors: {
                primary: {
                    DEFAULT: '#202A44',
                    dark: '#001428',
                    light: '#1a2b3c',
                    footer: '#1f2d47',
                },
                accent: {
                    DEFAULT: '#55c5e9',
                    teal: '#02635E',
                    'teal-light': '#9CB7B2',
                    cyan: '#7DCAD8',
                },
                text: {
                    DEFAULT: '#102132',
                    secondary: '#7a8796',
                },
                border: {
                    DEFAULT: '#d0d0d0',
                    light: '#e0e5eb',
                    mobile: '#a8b8c8',
                },
            },
            spacing: {
                '30': '30px',
                '34': '34px',
                '65': '65px',
                '87': '87px',
                '116': '116px',
                '140': '140px',
                '150': '150px',
                '195': '195px',
                '305': '305px',
                '405': '405px',
                '522': '522px',
                '847': '847px',
                '1059': '1059px',
            },
            fontSize: {
                '34': '34px',
            },
            lineHeight: {
                '56': '56px',
                '66': '66px',
            },
        },
    },
    variants: {},
    plugins: [],
  }
