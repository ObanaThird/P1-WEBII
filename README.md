<h1>📄 Products Management API Documentation</h1>

<p><i>
 Baseada no padrão MVC e realizando operações CRUD, esta aplicação gerencia produtos e cria logs das operações realizadas pela API.
</p></i>

<h3><a href="https://documenter.getpostman.com/view/37986149/2sAXqzWyKi">Documentação do Postman</a></h3>

<hr />

###

<h2>Como os Logs são gerados?</h2>

![image](https://github.com/user-attachments/assets/6fa321ed-520a-4271-990d-33cb950a76bd)


<p><i>
  Na camada controller, após as verificações da entrada de dados, um novo objeto do tipo Product é criado com os dados tratados e instanciado como parâmetro de um novo objeto do tipo ProductRepository.
</p>
<p>
  Após esse registro, um novo objeto do tipo LogController é criado recebendo um objeto do tipo Product e o método em que a requisição está acontecendo.
</p></i>

<h3>Camadas do Log</h3>

![image](https://github.com/user-attachments/assets/4fbf3bbf-fdc3-481c-848c-592881d318d2)


<p><i>
  Com os parâmetros recebidos da ProductController, um novo objeto do tipo LogModel é criado com os dados do produto e do método utilizado, e é instanciado como parâmetro de um objeto do tipo LogsRepository. A operação retorna falso caso nenhuma linha do banco de dados seja afetada.
</p></i>

###

<h2>Primeiro problema resolvido com lógica</h2>
<h4>$idProduct durante o insertProduct()</h4>

![image](https://github.com/user-attachments/assets/723a818e-f7ea-47d6-87ae-da4d9f155f97)

<p><i>
  Tanto a função de atualizar um produto existente no banco de dados quanto a função de criar um novo produto deveriam gerar um log. Para um produto existente a solução era óbvia dados os atributos da tabela logs:
  <ul>
    <li>id; (gerado automáticamente)</li>
    <li>operationType;</li>
    <li>date_time;</li>
    <li>idProduct.</li>
  </ul>
</p>

<p>
  Mas para a função insertProducts() a solução demandou um pouco de lógica:
</p>
</i>

![image](https://github.com/user-attachments/assets/30f36082-9ccf-449c-b056-78e2d991f702)

<p><i>
  No caso da função insertLogs() ser chamada a partir da função postProducts(), uma consulta no banco é feita antes de executar a inserção do log na tabela Logs para verificar qual foi o id da ultima linha criada na tabela Products. Caso encontre, retorna qual foi o 
  útilmo id criado.
</i></p>

![image](https://github.com/user-attachments/assets/0c0cfff9-896e-413b-a55a-6bd920a919f8)

<p><i>
  Cada etapa quando é concluída retorna verdadeiro ou falso dependo da legibilidade da função em questão para retornar a resposta da requisição na camada Controller.
</p></i>

###

<h2>Validações dos campos</h2>

<p><i>
  Durante o desenvolvimento, notei que a nível de organização de código, poderia ser uma boa prática separar as funções que validam a entrada dos dados em um lugar só.
</p></i>

![image](https://github.com/user-attachments/assets/4501f858-110f-47da-b225-c306970757da)


<p><i>
  As funções são bem simples de modo geral, possuindo validações com if e retornando verdadeiro ou falso para tratar o resultado na camada Controller.
</p>
<p>
  A função mais elaborada é a validateFields(), com um pouco de lógica, ela recebe os dados recebidos no corpo da requisição, quais campos são os campos necessários e verifica se todos esses campos foram preenchidos. Caso haja campos em branco, a função retorna esses campos.
</p>
</i>

![image](https://github.com/user-attachments/assets/e941f8de-273f-49e8-a64d-c5609fc2bcdc)

![image](https://github.com/user-attachments/assets/adeb6b92-0367-4a6c-90f1-5b2ac8c0f400)

###

<h2>Considerações finais</h2>

<p><i>
  Esse projeto exigiu conhecimento além do que eu já possuía:
  <ul>
    <li>Até o início do projeto, eu não sabia como enviar dados no formato JSON pelo corpo da requisição, tampouco como lidar com esses dados na aplicação;</li>
    <li>Não sabia como usar a data do sistema usando PHP, até então só tinha visto como usar direto do banco de dados ou do sistema em C# e Java;</li>
    <li>Além de não estar presente na aula em que foi explicado como criar a documentação do postman, o uso durante o projeto foi o meu primeiro contato com a ferramenta.</li>
  </ul>
</i></p>
