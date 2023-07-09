# gcloudののコマンドを使ってのデプロイ
gcloud functions deploy allabout-ogawa-functions \
  --region=us-west1 \
  --runtime=php82 \
  --source=src \
  --entry-point=send \
  --allow-unauthenticated \
  --trigger-http \
  --env-vars-file=.env.yaml
