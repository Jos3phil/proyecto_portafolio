
import bcrypt
import mysql.connector
from mysql.connector import Error

# Lista de nombres proporcionados
nombres_con_titulos = [
    "DR. LAURO ENCISO RODAS",
    "MGT. JULIO CÉSAR CARBAJAL LUNA",
    "MGT. NILA ZONIA ACURIO USCA",
    "MGT. JAVIER ARTURO ROZAS HUACHO",
    "MGT. LINO PRISCILIANO FLORES PACHECO",
    "MGT. EDWIN CARRASCO POBLETE",
    "DR. EMILIO PALOMINO OLIVERA",
    "DR. DENNIS IVÁN CANDIA OVIEDO",
    "DR. RONY VILLAFUERTE SERNA",
    "ING. GUZMÁN TICONA PARI",
    "MGT. YESHICA ISELA ORMEÑO AYALA",
    "ING. IVÁN CÉSAR MEDRANO VALENCIA",
    "MGT. LUIS BELTRÁN PALMA TTITO",
    "DR. ROBERT WILBERT ALZAMORA PAREDES",
    "MGT. WALDO ELIO IBARRA ZAMBRANO",
    "MGT. KARELIA MEDINA MIRANDA",
    "MGT. JAVIER DAVID CHÁVEZ CENTENO",
    "MGT. VANESSA MARIBEL CHOQUE SOTO",
    "ING. MANUEL AURELIO PEÑALOZA FIGUEROA",
    "LIC. JOSÉ MAURO PILLCO QUISPE",
    "ING. LINO AQUILES BACA CÁRDENAS",
    "LIC. ESTHER PACHECO VÁSQUEZ",
    "MGT. WILLIAN ZAMALLOA PARO",
    "MGT. HARLEY VERA OLIVERA",
    "MGT. MARITZA KATHERINE IRPANOCA CUSIMAYTA",
    "MGT. EFRAINA GLADYS CUTIPA ARAPA",
    "DR. DARIO FRANCISCO DUEÑAS BUSTINZA",
    "MGT. DORIS SABINA AGUIRRE CARBAJAL",
    "MGT. TANY VILLALBA VILLALBA",
    "MGT. CARLOS FERNANDO MONTOYA CUBAS",
    "MGT. CARLOS RAMÓN QUISPE ONOFRE",
    "MGT. BORIS CHULLO LLAVE",
    "MGT. HÉCTOR UGARTE ROJAS",
    "MGT. LUIS ALVARO MONZON CONDORI",
    "DR. EDGAR QUISPE CCAPACCA",
    "MGT. ELIDA FALCON HUALLPA",
    "MGT. VICTOR DARIO SOSA JAUREGUI",
    "MGT. JISBAJ GAMARRA SALAS",
    "MGT. HENRY SAMUEL DUEÑAS DE LA CRUZ",
    "MGT. RAUL HUILLCA HUALLPARIMACHI",
    "MGT. GERAR FRANCIS QUISPE TORRES",
    "ING. MARCIO FERNANDO MERMA QUISPE",
    "ING. VANESA LAVILLA ALVAREZ",
    "ING. SHIRLEY RUTH VELAZQUE ROJAS",
    "ING. JOSE GUILLERMO ANDRADE CARI",
    "DR. HANSH HARLEY CCACYAHUILLCA BÉJAR",
    "ING. LISHA SABAH DIAZ CACERES",
    "ING. STEPHAN JHOEL COSIO LOAIZA",
    "MGT. RAY DUEÑAS JIMENEZ",
    "ING. GABRIELA ZUÑIGA ROJAS",
    "ING. RAIMAR ABARCA MORA",
    "ING. LUZ INDIRA GUZMAN FELIX",
    "MGT. CLAUDIA FRANCESCA SUAREZ",
    "MGT. MARÍA DEL PILAR VENEGAS VERGARA",
    "ING. AGUEDO HUAMANI HUAYHUA",
    "MGT. OLMER CLAUDIO VILLENA LEÓN",
    "ING. JONEL CCENTE ZUZUNAGA",
    "MGT. VICTOR GABRIEL JALISTO",
    "ING. ALEX FERNANDO HUILLCA HUAMAN",
    "ING. NOHELY LISSETH OCHOA HUAYHUA",
    "ING. ALFREDO COLLANTES MENDOZA",
    "ING. ELMER CAMA CACERES"
]

# Configuración de la conexión a la base de datos
DB_CONFIG = {
    'host': '127.0.0.1',
    'user': 'root',
    'password': '',
    'database': 'bdportafolio'
}

# Contraseña por defecto
DEFAULT_PASSWORD = 'info123'

# Sufijo del correo electrónico
EMAIL_SUFFIX = '@unsaac.edu.pe'

# Lista de títulos a eliminar
TITULOS = ['Dr.', 'Mgt.', 'ING.', 'LIC.', 'DR.', 'MGT.', 'ING', 'LIC']

# Función para limpiar los nombres eliminando títulos
def limpiar_nombre(nombre):
    for titulo in TITULOS:
        nombre = nombre.replace(titulo, '').strip()
    return nombre

# Función para generar el correo electrónico
def generar_email(nombre_completo):
    partes = nombre_completo.split()
    if len(partes) < 2:
        raise ValueError(f"Nombre incompleto: {nombre_completo}")
    primer_nombre = partes[0].lower()
    apellido_paterno = partes[1].lower()
    email = f"{primer_nombre}.{apellido_paterno}{EMAIL_SUFFIX}"
    return email

# Función para hash de la contraseña
def hash_password(plain_password):
    hashed = bcrypt.hashpw(plain_password.encode('utf-8'), bcrypt.gensalt())
    return hashed.decode('utf-8')

# Función para obtener el id_usuario máximo actual
def get_max_id_usuario(connection):
    try:
        cursor = connection.cursor()
        cursor.execute("SELECT MAX(id_usuario) FROM TUsuarios")
        result = cursor.fetchone()
        max_id = result[0]
        if max_id:
            numero = int(max_id.replace('U', '')) + 1
        else:
            numero = 1
        return f"U{str(numero).zfill(3)}"
    except Error as e:
        print(f"Error al obtener el id_usuario máximo: {e}")
        return None

# Función para generar comandos SQL
def generar_comandos_sql(nombres, connection):
    comandos_sql = []
    current_id = get_max_id_usuario(connection)
    if not current_id:
        print("No se pudo obtener el id_usuario inicial.")
        return comandos_sql

    for nombre_original in nombres:
        nombre_limpio = limpiar_nombre(nombre_original)
        try:
            email = generar_email(nombre_limpio)
        except ValueError as ve:
            print(f"Error: {ve}")
            continue
        password_hashed = hash_password(DEFAULT_PASSWORD)
        # Escapar comillas simples en el nombre
        nombre_escapado = nombre_limpio.replace("'", "''")
        comando_insert = f"INSERT INTO TUsuarios (id_usuario, Nombre, email, password) VALUES ('{current_id}', '{nombre_escapado}', '{email}', '{password_hashed}');"
        comandos_sql.append(comando_insert)
        print(f"Usuario generado: {nombre_limpio}, Email: {email}, ID: {current_id}")
        # Incrementar el id_usuario
        numero = int(current_id.replace('U', '')) + 1
        current_id = f"U{str(numero).zfill(3)}"
    return comandos_sql

# Función para guardar los comandos SQL en un archivo
def guardar_comandos_sql(comandos, archivo_salida='insert_tusuarios.sql'):
    try:
        with open(archivo_salida, 'w', encoding='utf-8') as f:
            for comando in comandos:
                f.write(comando + '\n')
        print(f"\nComandos SQL guardados en '{archivo_salida}'")
    except Exception as e:
        print(f"Error al guardar el archivo SQL: {e}")

def main():
    try:
        connection = mysql.connector.connect(**DB_CONFIG)
        if connection.is_connected():
            print("Conexión a la base de datos establecida.")
            comandos = generar_comandos_sql(nombres_con_titulos, connection)
            if comandos:
                guardar_comandos_sql(comandos)
    except Error as e:
        print(f"Error al conectar a la base de datos: {e}")
    finally:
        if 'connection' in locals() and connection.is_connected():
            connection.close()
            print("Conexión a la base de datos cerrada.")

if __name__ == "__main__":
    main()