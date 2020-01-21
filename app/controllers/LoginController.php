<?php

declare(strict_types=1);

use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Db\Adapter\Pdo\Postgresql as Connection;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Manager;

class LoginController extends \Phalcon\Mvc\Controller
{

    public function autenticationAction()
    {

        //Request
        //Criando novos objetos de Request e Response
        $request = new Request();
        $response = new Response();
        
        //É request POST?
        if ($this->request->isPost()) {
            
            //É ajax?
            if($this->request->isAjax()) {

                //Pegando dados do formulário
                $username = $this->request->getPost('login');
                $password = $this->request->getPost('password');
                $code     = $this->request->getPost('code');

                //Executa a query no banco de dados e salva o resultado
                $users = $this->modelsManager->executeQuery(
                    "SELECT * FROM Teste WHERE username = '$username' AND password = '$password' OR email = '$username' AND password = '$password'"
                );

                //Looping para percorrer os resultados da query
                foreach ($users as $user) {
                    $result    = $user->username; //Se houver um registro será guardado aqui
                    $twoFactor = $user->twoFactor;//Aqui o mesmo
                }

                //Se $result não foi vazio fazer:
                if($result) {

                    //Verifica se autenticação em dois fatores está ativada
                    if(!$twoFactor) {
                        //se não usuário logado com sucesso
                        $message = "Logado com sucesso!";
                        $response->setStatusCode(200, 'Ok');
                      
                      //Caso esteja ativada:  
                    } else if($twoFactor == true){

                        //Se o campo código estiver vazio
                        if($code == '') {
                            $message = "Código de autenticação enviado em seu email";
                            $response->setStatusCode(200, 'Ok');
                            $randomCode = rand(1000, 9999);
                            $sentCode = $randomCode;
                            $users = $this->modelsManager->executeQuery(
                                "UPDATE Teste SET two_factor_code = '$randomCode' where username = '$username'"
                            );
                          
                          // Se não estiver será feita a autenticação com o existente no banco de dados
                        } else {
                            $users = $this->modelsManager->executeQuery(
                                "SELECT * FROM Teste WHERE two_factor_code = '$code'"
                            );

                            foreach ($users as $user) {
                                $twoFactorCode = $user->TwoFactorCode;
                            }

                            if($code = $twoFactorCode) {

            
                                $message = "Logado com sucesso!";
                                $response->setStatusCode(200, 'Ok');
                            } else {
                                
                                $sentCode = $randomCode;
                                $message = "Código Inválido";
                                $response->setStatusCode(401, 'Unauthorized');
                            }
                        }

                    }
                
                //Se $result for nulo o login não é válido, define flash message a ser exibida
                //juntamente com o código de status
                }else {

                    $message = "Login inválido";
                    $response->setStatusCode(401, 'Unauthorized');
                }
            }
            
        }
        
        //Define os dados que serão enviados na response
        $responseContent = [

            'code'       => $sentCode,
            'message'    => $message,
            'twoFactor'  => $twoFactor
        ];

        //Converte a array com os dados a serem enviados em Json e por fim envia a response
        $response
            ->setJsonContent($responseContent)
            ->send();
           

    }

}

