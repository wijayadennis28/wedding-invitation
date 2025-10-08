<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Fonts: Allura, Montserrat, and Playfair Display -->
    <link href="https://fonts.googleapis.com/css2?family=Allura&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS for enhanced responsive design -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'xs': '375px',
                        'sm': '640px',
                        'md': '768px',
                        'lg': '1024px',
                        'xl': '1280px',
                        '2xl': '1536px',
                    },
                    fontSize: {
                        '2xs': '0.625rem',
                        'xs': '0.75rem',
                        'sm': '0.875rem',
                        'base': '1rem',
                        'lg': '1.125rem',
                        'xl': '1.25rem',
                        '2xl': '1.5rem',
                        '3xl': '1.875rem',
                        '4xl': '2.25rem',
                        '5xl': '3rem'
                    },
                    spacing: {
                        'safe-top': 'env(safe-area-inset-top)',
                        'safe-bottom': 'env(safe-area-inset-bottom)',
                        'safe-left': 'env(safe-area-inset-left)',
                        'safe-right': 'env(safe-area-inset-right)',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('font-sans antialiased'); ?>>
<?php wp_body_open(); ?>

<!-- <div id="page-loader" class="fixed inset-0 bg-white z-50 flex items-center justify-center">
    <div class="text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-rose-500 mx-auto mb-4"></div>
        <p class="text-rose-600 font-serif">Loading...</p>
    </div>
</div> -->


