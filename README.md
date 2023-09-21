# 네이버웍스 API v2 for PHP

## 네이버웍스 API
네이버가 서비스 하는 협업툴인 "네이버웍스"의 API를 사용하기 위한 PHP 패키지 입니다.

참고 : [네이버웍스 API v2](https://developers.worksmobile.com/kr/docs/api)

## 설치
```bash
composer require evanskim/naverworks-api
```

## 설정

네이버웍스 개발자 콘솔에서 필요한 값을 확인 후 .env 파일에 추가합니다.

[네이버웍스 개발자 콘솔](https://dev.worksmobile.com/kr/console/openapi/v2/app/list/view)
### .env

```dotenv
NAVERWORKS_CLIENT_ID=nFY7qQs7oxLv******
NAVERWORKS_CLIENT_SECRET=4yksy9*****
NAVERWORKS_SERVICE_ACCOUNT=9hzxr.serviceaccount@****.com
NAVERWORKS_PRIVATE_KEY_PATH=private_20230920153318.key#다운받은 키파일의 경로
NAVERWORKS_DOMAIN_ID=226**
NAVERWORKS_BOT_ID=53259**
NAVERWORKS_TEST_USER_ID=37****-3***-4***-18**-033******# 네이버웍스 메시지 수신 테스트 할 사용자 ID ( 이메일 아님 )
```

## 테스트
설정값을 제대로 입력했다면 테스트를 통해 확인할 수 있습니다.
```bash
 vendor/bin/phpunit tests
```

## 사용법
### 메시지 전송
```php
    // 인증을 받아 엑세스토큰을 받아온 클라이언트를 생성합니다.
    $auth = new NaverWorksAuthorization(
        $_ENV['NAVERWORKS_CLIENT_ID'],
        $_ENV['NAVERWORKS_CLIENT_SECRET'],
        $_ENV['NAVERWORKS_SERVICE_ACCOUNT'],
        $path.$_ENV['NAVERWORKS_PRIVATE_KEY_PATH']
    );
    
    $bot = new ChatBot($_ENV['NAVERWORKS_BOT_ID'], $auth->createApiClient());
    $bot->to($_ENV['NAVERWORKS_TEST_USER_ID']);
    $bot->sendMessageToUser('test message');
```
