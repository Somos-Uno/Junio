<?php

if ( ! function_exists( 'junio_setup' ) ) :
/**
 * Configuración.
 * 
 * @since Junio 1.0.0
 */
function junio_setup() {

	// Activar el soporte a las imagenes miniaturas en entradas y páginas.
	add_theme_support( 'post-thumbnails' );

	// Este tema utiliza wp_nav_menu() en dos lugares.
	register_nav_menus( array(
		'primary'   => __( 'Menú primario de la cabecera', 'junio' ),
		'secondary' => __( 'Menú secundario del pie', 'junio' ),
	) );

}
endif; // junio_setup
add_action( 'after_setup_theme', 'junio_setup' );

/**
 * Register área de widget.
 *
 * @since Junio 1.0.0
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function junio_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Área Widget', 'junio' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Añadir widgets aquí para aparecer en la barra lateral.', 'junio' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'junio_widgets_init' );

/**
 * Crear un texto elemento de título con un formato agradable y más específica para la salida
 * en la cabeza del documento, basado en la vista actual.
 *
 * @since Junio 1.0.0
 */
function junio_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Añadir el nombre del sitio.
	$title .= get_bloginfo( 'name', 'display' );

	// Añadir la descripción del sitio para la página de home/front.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Añadir un número de página si es necesario.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __( 'Página %s', 'junio' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'junio_wp_title', 10, 2 );

if ( ! function_exists( 'junio_breadcrumb' ) ) :
/**
 * Mostrar las migras de pan.
 *
 * @since Junio 1.0.0
 */
function junio_breadcrumb() {
	$txtHome = 'Inicio';

	if (is_home() || is_404() || !have_posts())
		return;
	
	echo '<ol class="breadcrumb">';
	if (!is_home()) {
			echo '<li><a href="';
			echo get_option('home');
			echo '">';
			echo ' '.$txtHome;
			echo "</a></li>";
			if (is_category() || is_single()) {
					echo '<li>';
					the_category(' </li><li> ');
					if (is_single()) {
							echo "</li><li>";
							the_title();
							echo '</li>';
					}
			} elseif (is_page()) {
					echo '<li>';
					echo the_title();
					echo '</li>';
			}
	}
	echo '</ol>';
}
endif;

if ( ! function_exists( 'junio_post_thumbnail' ) ) :
/**
 * Mostrar un imagen en miniatura opcional.
 *
 * @since Junio 1.0.0
 */
function junio_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
		<?php the_post_thumbnail( 'large', array( 'class' => 'img-responsive', 'alt' => get_the_title() ) ); ?>
	</div><!-- .post-thumbnail -->

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php
			the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-responsive', 'alt' => get_the_title() ) );
		?>
	</a>

	<?php endif;
}
endif;
