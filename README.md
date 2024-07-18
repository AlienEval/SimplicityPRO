V2/?
Verificar token y IP:

    Comando: ?token=$token
    Descripción: Verifica si $token está activo y gestiona la IP del cliente.

Verificar IP:

    Comando: ?ip=!
    Descripción: Verifica si la IP del cliente está en la lista blanca o negra.

Agregar IP a la lista negra:

    Comando: ?addb=!
    Descripción: Agrega la IP del cliente a la lista negra.

Limpiar archivos:

    Comando: ?clear=!
    Descripción: Borra todos los archivos de datos.

Estado de la API:

    Comando: ?status=!
    Descripción: Retorna el estado actual de la API.

Editar datos del usuario por token:

    Comando: ?user=$token
    Descripción: Permite editar los datos asociados al usuario identificado por $token. Los parámetros GET opcionales permiten actualizar campos específicos como estado (status), tokens (ltoken, ctoken, mtoken), y datos adicionales (data1, data2, data3, par1, par2, par3). Si el archivo JSON del usuario no existe, se crea con los datos proporcionados.

Obtener datos del usuario por token:

    Comando: ?3d=$token
    Descripción: Obtiene los datos del usuario identificado por $token.

Eliminar archivo de token:

    Comando: ?clr=$token
    Descripción: Elimina el archivo de datos del usuario identificado por $token.

Incrementar strikes de un token:

    Comando: ?strk=$token
    Descripción: Incrementa el contador de strikes del usuario identificado por $token.

/users/?
Eliminar archivo JSON:

    Comando: ?clr=$token
    Descripción: Elimina el archivo JSON correspondiente al token $token en la carpeta /2fa.

Limpiar carpeta de archivos JSON:

    Comando: ?3d=clear
    Descripción: Elimina todos los archivos JSON presentes en la carpeta /2fa.

Obtener estado de la API:

    Comando: ?status=!
    Descripción: Retorna un mensaje de éxito indicando que la API está en funcionamiento ({"message":"success","status":"200 OK"}).

Actualizar archivo JSON por token:

    Comando: ?3d=$token&1=value1&2=value2&3=value3&4=value4&5=value5&6=value6
    Descripción: Actualiza el archivo JSON correspondiente al token $token en la carpeta /2fa. Permite actualizar los parámetros par1 a par6. Si el archivo no existe, se crea con los valores proporcionados.

Crear archivo JSON por token si no existe:

    Comando: ?3d=$token
    Descripción: Crea un nuevo archivo JSON en la carpeta /2fa si no existe uno para el token $token. También permite inicializar los parámetros par1 a par6 si se proporcionan valores.

/save/?
Editar campo en archivo JSON encriptando el valor:

    Comando: ?ltoken=$token&bearer=$data
    Descripción: Encripta el valor $data usando AES-256-CBC con la clave de encriptación proporcionada ($encryptionKey). Actualiza el campo ltoken en el archivo JSON correspondiente al token $token ubicado en la carpeta /users/.

Comprobación de acceso y parámetros:

El script valida la presencia de los parámetros ltoken, ctoken, mtoken, data1, data2, data3 y bearer. Solo procede si todos los parámetros requeridos ($param) y bearer están presentes en la solicitud GET.
Respuestas HTTP:

    Retorna un código de estado HTTP 200 si la operación se realiza correctamente.
    Retorna un código de estado HTTP 400 si no se proporcionan parámetros válidos para editar el JSON.
    Retorna un código de estado HTTP 404 si no se encuentra el archivo JSON asociado al token proporcionado.
    Retorna un código de estado HTTP 500 si hay algún error al guardar los cambios en el archivo JSON.

Este script PHP permite editar y encriptar campos específicos en archivos JSON ubicados en la carpeta /users/ utilizando técnicas de encriptación AES-256-CBC para asegurar los datos sensibles almacenados.
/save/dcr/?
Desencriptar campos en un archivo JSON:

    Comando: ?token=$fileName&key=$encryptionKey
    Descripción: Desencripta los campos ltoken, ctoken, mtoken, data1, data2, data3 en el archivo JSON correspondiente al nombre $fileName ubicado en la carpeta /users/. Utiliza la clave de encriptación $encryptionKey para realizar la operación.

Verificación de parámetros:

El script verifica la presencia de los parámetros token y key en la solicitud GET para proceder con la desencriptación.
Respuestas HTTP:

    Retorna un código de estado HTTP 200 junto con el contenido JSON desencriptado si la operación se realiza correctamente.
    Retorna un código de estado HTTP 400 si faltan parámetros obligatorios (token o key) en la solicitud.
    Retorna un código de estado HTTP 404 si no se encuentra el archivo JSON asociado al nombre de archivo proporcionado ($fileName).
    Retorna un código de estado HTTP 400 si hay errores en el formato JSON del archivo.

/pixel/?
Actualizar estado del token:

    Comandos disponibles:
        ?ltoken=$token
        ?ctoken=$token
        ?mtoken=$token
        ?ban=$token
        ?leave=$token
        ?finish=$token
        ?mfa=$token
        ?stk=$token
    Descripción: Actualiza el estado del archivo JSON correspondiente al token $token ubicado en el directorio /users/. El estado se actualiza según el parámetro GET proporcionado (ltoken, ctoken, mtoken, ban, leave, finish, mfa, stk).

Respuestas HTTP:

    Retorna un código de estado HTTP 200 junto con el número actualizado de strikes si la operación se realiza correctamente.
    Retorna un código de estado HTTP 404 si no se encuentra el archivo JSON asociado al token proporcionado.
    Retorna un código de estado HTTP 400 si la solicitud no contiene un parámetro válido para actualizar el estado (ltoken, ctoken, mtoken, ban, leave, finish, mfa, stk).

/deliver/?
Carga y entrega de tokens:

    ?loadtokens=!: Carga tokens desde active.txt a actload.txt, filtrando y reportando los válidos.
    ?newtoken=!: Obtiene y mueve el primer token válido de actload.txt a delivered.txt.
    ?verify=$token: Verifica si $token está en active.txt y devuelve true o false.



V2






Verificar token y IP:

    Comando: ?token=$token
    Descripción: Verifica si $token está activo y gestiona la IP del cliente.
    Ejemplo: ?token=abc123

Verificar IP:

    Comando: ?ip=!
    Descripción: Verifica si la IP del cliente está en la lista blanca o negra.
    Ejemplo: ?ip=192.168.1.1

Agregar IP a la lista negra:

    Comando: ?addb=!
    Descripción: Agrega la IP del cliente a la lista negra.
    Ejemplo: ?addb=192.168.1.1

Limpiar archivos:

    Comando: ?clear=!
    Descripción: Borra todos los archivos de datos.
    Ejemplo: ?clear=

Estado de la API:

    Comando: ?status=!
    Descripción: Retorna el estado actual de la API.
    Ejemplo: ?status=

Editar datos del usuario por token:

    Comando: ?user=$token&status=active&ltoken=newToken
    Descripción: Permite editar los datos asociados al usuario identificado por $token. Los parámetros GET opcionales permiten actualizar campos específicos como estado (status), tokens (ltoken, ctoken, mtoken), y datos adicionales (data1, data2, data3, par1, par2, par3). Si el archivo JSON del usuario no existe, se crea con los datos proporcionados.
    Ejemplo: ?user=abc123&status=active&ltoken=newToken

Obtener datos del usuario por token:

    Comando: ?3d=$token
    Descripción: Obtiene los datos del usuario identificado por $token.
    Ejemplo: ?3d=abc123

Eliminar archivo de token:

    Comando: ?clr=$token
    Descripción: Elimina el archivo de datos del usuario identificado por $token.
    Ejemplo: ?clr=abc123

Incrementar strikes de un token:

    Comando: ?strk=$token
    Descripción: Incrementa el contador de strikes del usuario identificado por $token.
    Ejemplo: ?strk=abc123

Eliminar archivo JSON:

    Comando: ?clr=$token
    Descripción: Elimina el archivo JSON correspondiente al token $token en la carpeta /2fa.
    Ejemplo: ?clr=abc123

Limpiar carpeta de archivos JSON:

    Comando: ?3d=clear
    Descripción: Elimina todos los archivos JSON presentes en la carpeta /2fa.
    Ejemplo: ?3d=clear

Actualizar archivo JSON por token:

    Comando: ?3d=$token&1=value1&2=value2&3=value3&4=value4&5=value5&6=value6
    Descripción: Actualiza el archivo JSON correspondiente al token $token en la carpeta /2fa. Permite actualizar los parámetros par1 a par6. Si el archivo no existe, se crea con los valores proporcionados.
    Ejemplo: ?3d=abc123&1=test1&2=test2&3=test3

Crear archivo JSON por token si no existe:

    Comando: ?3d=$token
    Descripción: Crea un nuevo archivo JSON en la carpeta /2fa si no existe uno para el token $token. También permite inicializar los parámetros par1 a par6 si se proporcionan valores.
    Ejemplo: ?3d=abc123&par1=value1&par2=value2

Editar campo en archivo JSON encriptando el valor:

    Comando: ?ltoken=$token&bearer=$data
    Descripción: Encripta el valor $data usando AES-256-CBC con la clave de encriptación proporcionada ($encryptionKey). Actualiza el campo ltoken en el archivo JSON correspondiente al token $token ubicado en la carpeta /users/.
    Ejemplo: ?ltoken=abc123&bearer=sensitiveData

Desencriptar campos en un archivo JSON:

    Comando: ?token=$fileName&key=$encryptionKey
    Descripción: Desencripta los campos ltoken, ctoken, mtoken, data1, data2, data3 en el archivo JSON correspondiente al nombre $fileName ubicado en la carpeta /users/. Utiliza la clave de encriptación $encryptionKey para realizar la operación.
    Ejemplo: ?token=file123&key=myEncryptionKey

Actualizar estado del token:

    Comando: ?ltoken=$token
    Descripción: Actualiza el estado del archivo JSON correspondiente al token $token ubicado en el directorio /users/. El estado se actualiza según el parámetro GET proporcionado (ltoken, ctoken, mtoken, ban, leave, finish, mfa, stk).
    Ejemplo: ?ltoken=abc123

Carga y entrega de tokens:

    Comando: ?loadtokens=!

    Descripción: Carga tokens desde active.txt a actload.txt, filtrando y reportando los válidos.

    Ejemplo: ?loadtokens=!

    Comando: ?newtoken=!

    Descripción: Obtiene y mueve el primer token válido de actload.txt a delivered.txt.

    Ejemplo: ?newtoken=!

Verificar si un token está en active.txt:

    Comando: ?verify=$token
    Descripción: Verifica si $token está en active.txt y devuelve true o false.
    Ejemplo: ?verify=abc123
