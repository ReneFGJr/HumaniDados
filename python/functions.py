import xml.etree.ElementTree as ET
import csv

def extract_general_info(xml_files, output_csv):
    fields = [
        "NUMERO-IDENTIFICADOR", "NOME-COMPLETO", "DATA-ATUALIZACAO",
        "PAIS-DE-NASCIMENTO", "UF-NASCIMENTO", "CIDADE-NASCIMENTO",
        "ORCID", "NOME-INSTITUICAO-EMPRESA", "NOME-ORGAO",
        "CIDADE", "UF"
    ]

    general_info_list = []

    for file in xml_files:
        tree = ET.parse(file)
        root = tree.getroot()

        numero_identificador = root.attrib.get("NUMERO-IDENTIFICADOR")
        data_atualizacao = root.attrib.get("DATA-ATUALIZACAO")

        dados_gerais = root.find(".//DADOS-GERAIS")
        nome_completo = dados_gerais.attrib.get("NOME-COMPLETO")
        pais_nascimento = dados_gerais.attrib.get("PAIS-DE-NASCIMENTO")
        uf_nascimento = dados_gerais.attrib.get("UF-NASCIMENTO")
        cidade_nascimento = dados_gerais.attrib.get("CIDADE-NASCIMENTO")
        orcid = dados_gerais.attrib.get("ORCID-ID")

        endereco = root.find(".//ENDERECO[@FLAG-DE-PREFERENCIA='ENDERECO_INSTITUCIONAL']/ENDERECO-PROFISSIONAL")
        nome_instituicao_empresa = endereco.attrib.get("NOME-INSTITUICAO-EMPRESA")
        nome_orgao = endereco.attrib.get("NOME-ORGAO")
        cidade = endereco.attrib.get("CIDADE")
        uf = endereco.attrib.get("UF")

        general_info = {
            "NUMERO-IDENTIFICADOR": numero_identificador,
            "NOME-COMPLETO": nome_completo,
            "DATA-ATUALIZACAO": data_atualizacao,
            "PAIS-DE-NASCIMENTO": pais_nascimento,
            "UF-NASCIMENTO": uf_nascimento,
            "CIDADE-NASCIMENTO": cidade_nascimento,
            "ORCID": orcid,
            "NOME-INSTITUICAO-EMPRESA": nome_instituicao_empresa,
            "NOME-ORGAO": nome_orgao,
            "CIDADE": cidade,
            "UF": uf
        }

        general_info_list.append(general_info)

    with open(output_csv, mode='w', newline='', encoding='utf-8') as file:
        writer = csv.DictWriter(file, fieldnames=fields)
        writer.writeheader()
        writer.writerows(general_info_list)
