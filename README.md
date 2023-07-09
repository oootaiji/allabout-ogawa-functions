# allabout-ogawa-functions
## 概要
Cloud Functionsを触ったことがないので学習
以前に実装したallabout-ogawa-webhookのメール送信するだけのアプリを作成し、
以前はGKEだったが、今回はCloud Functionsへデプロイすることで比較学習する。


## 成果物
```
Github
https://github.com/oootaiji/allabout-ogawa-functions

学習アウトプット
https://github.com/oootaiji/allabout-ogawa-functions/blob/main/CloudFunctionsを学習.md

アプリ
お金かかるため、デプロイしたらすぐ停止
```


## 目的・要件
### 目的
- 実践的な学習
    - 商用・業務で使うことを意識する
    - devopsを意識する
        - 本番環境はdockerコンテナが動いているが、ローカル開発環境もコンテナで動くようにする
        - devopsを意識して、開発環境と本番環境の連携を容易にする

### インフラ要件
- クラウド
    - Cloud Functions
        - 第２世代を使う
    - やらない
        - VPC (ネットワーク設計)
        - 運用設計・運用方針
- リポジトリ管理
    - Github
- CI/CD
    - Github Actions
    - デプロイのOSは、ubuntu20.04を使う

### アプリ要件
- allabout-ogawa-webhookに同じ



## 手動デプロイ
### Cloud Functionsの準備
- Cloud Functionsを利用するためのAPIを有効にしておく

    ```
    1. 課金を有効
    2. API を有効
        - Cloud Functions API
        - Cloud Build API
        - Artifact Registry API
        - Cloud Run Admin API
        - Cloud Logging API
        - Cloud Pub/Sub API
    ```

### SendGridの準備
- SendGridのSender Authenticationを設定
    - チュートリアルどおりに設定する


### デプロイ (Cloud Console)
- functions作成
    - 名前は `allabout-ogawa-functions` にする
    - 未承認呼び出しを許可を選択 (内部的なAPIではなく、公開APIにする)
    - ランタイム環境変数にSendGridのAPIキーを設定
    - コードを作成
- デプロイ
- デプロイ確認
    - https://hoge.com/allabout-ogawa-functions/関数名



## デプロイ自動化
### 1. サービスアカウント(権限)JSONの作成
- IAMでサービスアカウントを作成 (運用方針は定義してないのでこだわらない。すぐ削除する)
    - 以下の3つのロールを入れておく
        - Cloud Functions (とりあえず管理者でOK)
        - Artifact Registry (とりあえず管理者でOK)
        - ストレージ (とりあえず管理者でOK)
- Workload Identityを有効にする
    - APIを有効化
    - Workload Identityを作成
    - Service Accountに紐付ける

### 2. Github Actionsのyamlを作成
- .github/workflows/workflow.yml作成
- Githubに環境変数を設定 (config.yamlで使う環境変数を登録)
    - アプリで使う環境変数
    - サービスアカウントJSON


### 3. デプロイ
1. 手動デプロイは済ませておく
    1. 「未承認呼び出しを許可」は後で設定を変えられないので注意
2. mainへpush
3. デプロイ確認
    1. Actionsのworkflowが動く
    2. Actionsのworkflowが正常終了
    3. ブラウザにてアプリ稼働確認



## 参考
- [クイックスタート](https://cloud.google.com/functions/docs/console-quickstart?hl=ja)
- [環境変数の取得](https://cloud.google.com/functions/docs/samples/functions-env-vars?hl=ja)
- [gcloud deployコマンド](https://cloud.google.com/sdk/gcloud/reference/functions/deploy)
- [cloud functionsデプロイ方法まとめ](https://zenn.dev/akineko/articles/ed7e1c9437cc2c)
- [GitHub Action Cloud Functions Deploy](https://github.com/marketplace/actions/cloud-functions-deploy)
- [github actionsでoidcを利用してgcpの認証](https://zenn.dev/kou_pg_0131/articles/gh-actions-oidc-gcp)