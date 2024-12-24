# Endpoint-creation

***USERS***

*1. Endpoint Register*
   
URL: POST http://127.0.0.1:8000/api/v1/register

Objetivo:
Crear un nuevo usuario en la base de datos.

Detalles
-	Verbo HTTP: POST
-	Formato del cuerpo (Body): JSON
-	Ruta pública: no requiere autenticación previa.
-	Estructura de datos esperada en Body:
  
![image](https://github.com/user-attachments/assets/5ed58a43-59b6-47b2-ac49-e1ecf545932d)

Parámetros:
- Username: nombre de usuario deseado.
- Email: correo electrónico válido y único.
- PasswordHash: contraseña, la cual se hasheará internamente.

Estructura de respuesta esperada con respuesta exitosa (HTTP 201): 

![image](https://github.com/user-attachments/assets/9d478c25-0bd5-4893-ba33-c30a15148387)


*2. Endpoint Login*

URL: POST http://127.0.0.1:8000/api/v1/login

Objetivo: Autenticar a un usuario existente en la base de datos y, si las credenciales son correctas, generar un token de acceso.

Detalles 
- Verbo HTTP: POST
- Formato del cuerpo (Body): JSON
- Ruta pública: no requiere autenticación previa.
- Estructura de datos esperada en el cuerpo (Body):

	![image](https://github.com/user-attachments/assets/41b6b7e8-a6a1-4427-b9e0-9af2850277cd)

Parámetros requeridos
- Email: correo electrónico con el que se registró el usuario.
- PasswordHash: contraseña en texto plano que el sistema validará con su versión hasheada.

  Respuesta exitosa (HTTP 200):

	![image](https://github.com/user-attachments/assets/48e4ec93-53c8-4d68-b926-b5ae3814e073)

*3. Endpoint me*
   
URL: GET http://127.0.0.1:8000/api/v1/me

Objetivo: Obtener la información del usuario actualmente autenticado (con token válido).

Detalles Importantes
- Verbo HTTP: GET
- Formato del cuerpo: No se envían datos en el body.
- Ruta protegida: se requiere Bearer Token en el header Authorization.
- Cabeceras:
		Accept: application/json
		- Authorization: Bearer <token>

	![image](https://github.com/user-attachments/assets/5119ba1a-81c9-4fb5-81be-31a153521e5a)

*4. Endpoint logout*
   
URL: POST http://127.0.0.1:8000/api/v1/logout
Objetivo: Invalidar (desautenticar) el token actual del usuario, y que así no pueda seguir accediendo a las rutas protegidas.

Detalles Importantes
•	Verbo HTTP: POST
•	Ruta protegida: se requiere Bearer Token en el header Authorization.

![image](https://github.com/user-attachments/assets/6a52463f-134b-49e5-b33e-b95d175373fc)


***SHOPPING CART***

*1. Mostrar carrito*
Descripción: Muestra el contenido del carrito de compras con Status = "open"para el usuario autenticado. Si el usuario no tiene un carrito abierto, devolverá qn mensaje de error.

Método :GET
- URL: http://127.0.0.1:8000/api/v1/shopping-cart
- Protección : Requiere cabecera de autenticación Authorization: Bearer {token}

Respuesta Exitosa (HTTP 200)

![image](https://github.com/user-attachments/assets/b7016a98-f625-45cc-9570-c26a05c07b05)

"items"Contiene una lista de objetos con información del ítem ( CartItemID, VariantID, Quantity, UnitPrice) y detalles opcionales ( Color, Size) provenientes de la relación productVariant.

Si no existe un carrito abierto , se devuelve un mensaje:
json: "message": "The user does not have an open cart".


*2. Agregar un Producto (Variante) al Carrito*
Descripción: Este endpoint agrega un nuevo artículo al carrito abierto del usuario. Si no existe un carrito, crea uno automáticamente con Status = "open". Si el producto (variante) ya existe en el carrito, simplemente suma la cantidad.

Método :POST
- URL: http://127.0.0.1:8000/api/v1/shopping-cart/add
- Protección : Requiere cabecera de autenticación Authorization: Bearer {token}.

   ![image](https://github.com/user-attachments/assets/d08ec6f5-0583-4cc2-b21a-fecee1859cfd)


- VariantID: ID de la variante de producto que se va a agregar.
- Quantity: Cantidad a añadir (mínimo 1).

Respuesta Exitosa (HTTP 201):

  ![image](https://github.com/user-attachments/assets/f4f9602c-2659-4229-b881-93235bf28e79)

- Si la variante no existe en la tabla product_variants: JSON{
  "message": "The Product Variant doesnt exist"
}


- Si el artículo ya existía en el carrito, se aumenta el campo Quantity.


 * 3. Actualizar la Cantidad de un Producto en el Carrito*
      
Descripción: Actualiza la Quantityde un artículo específico del carrito, siempre que el artículo pertenezca al usuario autenticado.

Método :PUT
- URL: http://127.0.0.1:8000/api/v1/shopping-cart/update/{id}
- Protección : Requiere cabecera de autenticación Authorization: Bearer {token}.

  ![image](https://github.com/user-attachments/assets/0879520c-03cd-4c96-a017-00f312a8746f)

Respuesta Exitosa (HTTP 200):

  ![image](https://github.com/user-attachments/assets/eca1c372-0e9e-47e5-90a0-cb6833f09914)


- Si el artículo ( CartItemID) no existe o no pertenece al carrito del usuario, se retorna un 404 con el mensaje:
  Json: {
  "message": "Not Found Item"
}

*4. Eliminar un Producto del Carrito*
Descripción: Eliminar un artículo específico del carrito. Si el artículo no pertenece al usuario o no existe, retornará un error 404 .

Método :DELETE
- URL: http://127.0.0.1:8000/api/v1/shopping-cart/remove/{id}
- Protección : Requiere cabecera de autenticación Authorization: Bearer <token>.

Respuesta Exitosa (HTTP 200): 

   ![image](https://github.com/user-attachments/assets/5f5d46f5-90bf-4ce8-83df-1a186154932c)

- Si el artículo no se encuentra o no es del usuario autenticado, se retorna: json: {
  "message": "The item was not found or does not belong to the user"
}


***PRODUCTS***


*1. Obtener productos*

Objetivo : Listar todos los productos con sus variantes.

- MÉTODO: GET
- URL: http://127.0.0.1:8000/api/v1/products
  
Respuesta exitosa (200) : Un JSON con un array de todos los productos, cada uno incluyendo la relación productVariants.


*2. Crear un nuevo producto*

- MÉTODO: POST
- URL: http://127.0.0.1:8000/api/v1/products
- En Body raw:

  ![image](https://github.com/user-attachments/assets/de74b2c1-235d-4408-9471-5b1f19aef44b)

	Respuesta Exitosa (HTTP 201 Created):

  ![image](https://github.com/user-attachments/assets/543fa798-35b8-4ad8-98db-66cb81780545)

*3. Detalles de un producto*

Objetivo: Obtener los detalles de un producto específico

- MÉTODO: GET
- URL: http://127.0.0.1:8000/api/v1/products/{id}


Respuesta exitosa (HTTP 200):

![image](https://github.com/user-attachments/assets/8a580b3f-baa6-46db-b48c-c1da611c519a)


- Si no existe el producto con el id especificado devuelve: Json: {
  "message": "Product not found"
} (Con http 404)


*4. Actualizar producto*

Objetivo: Actualizar la información de un producto existente
- MÉTODO: PUT
- URL: http://127.0.0.1:8000/api/v1/products/{id}

*5. Eliminar producto*
Objetivo: Eliminar producto existente por ProductID

- MÉTODO: DELETE
- URL: http://127.0.0.1:8000/api/v1/products/{id}

Respuesta exitosa (HTTP 200)

![image](https://github.com/user-attachments/assets/8531640b-c801-458e-b93c-989bec844086)

- Si no se encuentra el producto, se retorna: json: {
  "message": "Product not found"
}


*6. Buscar producto por característica específica*

Objetivo : Búsqueda avanzada de productos. Se pueden combinar varios parámetros de forma opcional:

- name: Filtro donde ProductNamecontiene el valor (búsqueda parcial).
- color: Filtra productos que tengan variantes con Colorcoincidencia.
- size: Filtra productos que tengan variantes con Sizeexacto.
- price: Productos de filtrado con Price <= precio_solicitado.
- brand, collection,genre : Filtra en memoria los valores que se guardaron en OtherAttributes.

- MÉTODO: GET
- URL: http://127.0.0.1:8000/api/v1/products/search?brand=Bershka&collection=Shirt&genre=F

Respuesta exitosa (200) :

![image](https://github.com/user-attachments/assets/f2f818e1-443b-4b04-b6f5-8ea67b7f20a4)


***ORDERS***

*1. Crear orden para el usuario*

- Descripción : Este endpoint permite crear una nueva orden basada en los productos que el usuario tiene en su carrito de compras abierto.
- Método HTTP :POST
- URL: http://127.0.0.1:8000/api/v1/products/order
- Autenticación : Requiere autenticación con token de Sanctum ( auth:sanctum).
- Flujo :
		- El sistema obtiene el carrito de compras del usuario autenticado que tiene el estado open.
		- Verifique que el carrito no esté vacío.
		- Crea la orden con el total de los productos en el carrito.
		- Crea los artículos de la orden basados ​​en los productos del carrito (con variantes de producto).
		- Cambia el estado del carrito a closeduna vez creada la orden.

Respuesta exitosa (HTTP 201)

 ![image](https://github.com/user-attachments/assets/01b84f26-964d-441d-84ac-1f2f275fc72c)



*2. Listar todas las Órdenes del Usuario*

- MÉTODO: GET 
- URL: http://127.0.0.1:8000/api/v1/products/orders
- Descripción : Este endpoint permite obtener todas las órdenes del usuario autenticado.

- Autenticación : Requiere autenticación con token de Sanctum ( auth:sanctum).
- Flujo :
		- El sistema consulta todas las órdenes asociadas al usuario autenticado.
		- Cargue los artículos de cada pedido, incluyendo detalles de los productos y variantes asociadas.


*3. Obtener los Detalles de una Orden Específica*

- MÉTODO: GET 
- URL: /api/v1/products/orders/{id}
- Descripción : Este punto final permite obtener los detalles completos de una orden específica del usuario autenticado, incluyendo los ítems de la orden y los productos.
- Autenticación : Requiere autenticación con token de Sanctum ( auth:sanctum).
- Parámetros :
		- id: El ID de la orden que deseas consultar.
- Flujo :
		- El sistema busca la orden correspondiente al ID proporcionado y la asocia con el usuario autenticado.
		- Cargue los artículos de la orden, incluyendo los productos y variantes asociadas.

Respuesta exitosa (200 OK)

![image](https://github.com/user-attachments/assets/76b805bf-efb8-4386-9ada-581def06940e)

![image](https://github.com/user-attachments/assets/2c4a8d7f-8693-4df5-aa9f-977339f85664)




***ADJUNTO .JSON => POSTMAN:***


[Uploading SQ1-Academy API.postman_collection.json…]()



 








