import sys
import openpyxl
import pymysql

def process_excel(file_path, database_name="railway", user="root", password="ftMKHcANburGUFcaNNnVMJqnLQZRBvGo", host="viaduct.proxy.rlwy.net", port=11013):
    """
    Importa datos desde un archivo Excel a una tabla MySQL.

    Args:
        file_path (str): Ruta al archivo Excel.
        database_name (str, optional): Nombre de la base de datos MySQL. Por defecto es "railway".
        user (str, optional): Nombre de usuario de MySQL. Por defecto es "root".
        password (str, optional): Contraseña de MySQL. Por defecto es "ftMKHcANburGUFcaNNnVMJqnLQZRBvGo".
        host (str, optional): Nombre del host de MySQL. Por defecto es "viaduct.proxy.rlwy.net".
        port (int, optional): Puerto de MySQL. Por defecto es 11013.
    """
    connection = None
    cursor = None

    try:
        # Conectar a la base de datos MySQL (usando pymysql)
        connection = pymysql.connect(
            database=database_name, user=user, password=password, host=host, port=port
        )
        cursor = connection.cursor()

        # Cargar el libro de Excel
        wb = openpyxl.load_workbook(file_path, data_only=True)
        sheet = wb.active

        # Extraer la fila de encabezado (asumiendo que es la primera fila)
        header_row = [cell.value for cell in sheet[1]]

        # Preparar la consulta INSERT con marcadores de posición
        insert_query = f"""
            INSERT INTO bluewis ({', '.join(header_row)})
            VALUES ({', '.join(['%s'] * len(header_row))})
        """

        # Insertar datos fila por fila (omitir la fila de encabezado)
        for row_index in range(2, sheet.max_row + 1):
            row_data = [cell.value for cell in sheet[row_index]]
            cursor.execute(insert_query, row_data)

        # Confirmar los cambios
        connection.commit()
        print("¡Datos importados con éxito!")

    except FileNotFoundError as e:
        print(f"Error: Archivo no encontrado: {e.filename}")
    except pymysql.MySQLError as e:
        print(f"Error de MySQL: {e}")
    except Exception as e:
        print(f"Error inesperado: {e}")
    finally:
        if cursor:
            cursor.close()
        if connection:
            connection.close()
        if wb:
            wb.close()

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Uso: python script.py <ruta_archivo_excel> [database_name user password host port]")
        sys.exit(1)

    process_excel(
        sys.argv[1],
        sys.argv[2] if len(sys.argv) > 2 else "railway",
        sys.argv[3] if len(sys.argv) > 3 else "root",
        sys.argv[4] if len(sys.argv) > 4 else "ftMKHcANburGUFcaNNnVMJqnLQZRBvGo",
        sys.argv[5] if len(sys.argv) > 5 else "viaduct.proxy.rlwy.net",
        int(sys.argv[6]) if len(sys.argv) > 6 else 11013,
    )