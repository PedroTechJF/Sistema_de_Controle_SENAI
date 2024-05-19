
# Sistema de Controle SENAI

Esse sistema foi proposto como uma atualização do [Sistema de Controle de Frequência De Alunos](https://github.com/PedroTechJF/Sistema_de_Controle_de_Frequencia_De_Alunos), que foi uma atividade proposta no Curso Técnico de Desenvolvimento de Sistemas, como uma demanda real do SENAI CFP JFN.

A ideia inicial do sistema era realizar todo o controle da frequência dos alunos da instituição, permitindo o envio de relátorios mensais de faltas para empresas parceiras, as quais muitos alunos possuem vínculo.

Agora, além dessas funcionalidades, o sistema inclui área de cadastro para alunos e ex-alunos da instituição. Permitindo os mesmos acessarem o sistema para enviar Justificativas de Faltas (no caso de alunos), e Solicitar Documentos, como a 2ª via de carteirinha estudantil ou a 2ª via de Diploma.
## Tipos de Acesso

O sistema possui três tipos de acesso, com usuários individuais:

- Escola: 
    - Pode cadastrar uma nova empresa no sistema;
    - Listar Empresas Cadastradas, podendo editar seus dados ou até mesmo desativar seu acesso ao sistema;
    - Enviar os Relatórios de Falta Mensais;
    - Listar todos os Relatórios enviados, podendo realizar o download de cada um, editar seus dados ou até mesmo desativar seu acesso ao sistema;
    - Listar todos os Alunos cadastrados no sistema, podendo editar seus dados ou até mesmo desativar seu acesso ao sistema;
    - Listar todas as Justificativas de Faltas, podendo realizar o download de seu comprovante, e todas as Solicitações de Documentos, podendo visualizar o aluno que enviou/solicitou, em ambos os casos, e editá-las conforme necessário. 
        #### Exemplo
        ```
        Em uma Justificativa de Falta, a Escola pode Aprovar ou Negar. 
        Mas, em uma Solicitação de Documento, a Escola pode enviar um Boleto para
        pagamento, caso necessário, e marcar o status daquela solicitação como Pagamento
        Pendente, Pagamento Aprovado, Pagamento Negado ou Disponível para Retirada
        (todo documento é retirado fisicamente na unidade SENAI).
        ```

- Empresa:
    - Listar todos os Relatórios enviados para ela, podendo realizar o download de cada um;
    - Listar todas Justificativas de Faltas enviadas por alunos que possuem vínculo.

- Alunos:
    - Listar todas as Justificativas de Faltas e Solicitações de Documentos enviadas por ele, podendo visualizar seu status atual;
    - Enviar uma nova Justificativa de Falta.
    - Solicitar um novo Documento;

## Atualizações e Melhorias Gerais

O sistema também recebeu Atualizações e Melhorias Gerais:
 - Área de Cadastro para Alunos e Ex-alunos;
 - Área de Atualização e Recuperação de Senha;
 - Área do Usuário, onde será possível visualizar os dados do usuário logado no momento, e acessar uma página de atualização daquelas dados;
  - Campos não editáveis em áreas de envio/atualização contam com cor de fundo ofuscada - rgba(255, 255, 255, 0.5);
  - A Visualização de Dados de forma geral foi melhorada;

## Funcionalidades

- Cadastro de Usuário para Alunos e Ex-alunos;
- Atualização e Recuperação de Senha;
- Cadastrar, Listar, Editar e Deletar Empresas;
- Cadastrar, Listar, Editar e Deletar Relatorios;
- Cadastrar, Listar e Editar Justificativas de Faltas;
- Cadastrar, Listar e Editar Solicitações de Documentos.
## Licença

[MIT](https://github.com/PedroTechJF/Sistema_de_Controle_SENAI/blob/main/LICENSE)
