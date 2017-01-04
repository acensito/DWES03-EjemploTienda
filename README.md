## EJEMPLO TIENDA

Este es un ejemplo de utilización de sesiones para almacenar la información del usuario.

Se adjunta el archivo con la **base de datos (BD\_Tienda.sql)** completa con todos los datos para importar en MySQL.

Las páginas de que constará tu tienda online son las siguientes:

![Esquema](http://acn.neocities.org/DWES04_CONT_R08_diagrama_cesta_compra.png)

- **Login (login.php)**. Su función es autentificar al usuario de la aplicación web. Todos los usuarios de la aplicación deberán autentificarse utilizando esta página antes de poder acceder al resto de páginas. No se usará claves encriptadas en este ejemplo.
- **Listado de productos (productos.php)**. Presenta un listado de los productos de la tienda, y permite al usuario seleccionar aquellos que va a comprar.
- **Cesta de compra (cesta.php)**. Muestra un resumen de los productos escogidos por el usuario para su compra y da acceso a la página de pago.
- **Pagar (pagar.php)**. Una vez confirmada la compra, la última página debería ser la que permitiera al usuario escoger el método de pago y la forma de envío. En este ejemplo no la vas a implementar como tal. Simplemente mostrará un mensaje de tipo &quot;Gracias por su compra&quot; y ofrecerá un enlace para comenzar una nueva compra.
- **Logoff (logoff.php)**. Esta página desconecta al usuario de la aplicación y redirige al usuario de forma automática a la pantalla de autentificación. No muestra ninguna información en pantalla, por lo que no es visible para el usuario.

Recuerda poner a las páginas los nombres que aquí figuran, almacenando todas en la misma carpeta. Si cambias algún nombre o mueves alguna página de lugar, los enlaces internos no funcionarán.

Aunque el aspecto de la aplicación no es importante para nuestro objetivo, utilizaremos:

- Una hoja de estilos **tienda.css** , común a todas las páginas
- Una imagen, **cesta.png** , para ofrecer un interface más amigable.

La aplicación que vas a desarrollar no es completamente funcional. Además de no desarrollar la página con la información de pago, habrá algunas opciones que no se tendrá en cuenta para simplificar el código. Por ejemplo:

- No tendrás en cuenta la posibilidad de que el usuario compre varias unidades de un mismo producto.
- Una vez añadido un producto a la cesta de compra, no se podrá retirar de la misma. La única posibilidad será vaciar toda la cesta y comenzar de nuevo añadiendo productos.
- No se mostrarán imágenes de los productos, ni será posible ver el total de la compra hasta que ésta haya finalizado.
- Se muestran todos los productos en una única página. Sería preferible filtrarlos por familia y mostrarlos en varias páginas, limitando a 10 o 20 productos el número máximo de cada página.
