# <プロジェクト名>

<プロジェクト説明>

## 環境

* FuelPHP: 1.7.2
* backlog URL: https://product.backlog.jp/

### 利用パッケージ

* ORM
* Fuel-Package-TraitOrmFormat http://qiita.com/goosys/items/f69c2bc11d619152fa80

# セットアップ

## 前準備

* [VirtualBox](https://www.virtualbox.org/wiki/Downloads), [Vagrant](https://www.vagrantup.com/downloads.html)をインストール
* 利用ディレクトリのパスに日本語が入っていないことを確認する。パスに日本語が入っていれば誤作動します。

## 実行
```
# リポジトリをclone
git clone xxxxxxxx
# リポジトリに移動
cd xxxxxxxx
# vagrant発動
vagrant up
```

## 確認

ブラウザで`http://localhost:8000/`を見て、表示されていれば成功です。

## メンテナンス

### phpmyadmin

```
http://localhost:8000/phpmyadmin/
Username: root
Password: なし
```

### Webmin

```
http://localhost:10001/
Username: root
Password: vagrant
```

### パッケージ管理

```
# インストール
./composer.phar install
# アップデート
./composer.phar update
```

### DBスキーマ管理

```
php oil r migrate
```

## 本番、動作確認環境の切り替え

デフォルトで開発(development)環境となっています。

### 本番(production)環境

#### コマンド
```
FUEL_ENV=production php oil refine migrate
```
のように、先頭に`FUEL_ENV=production`をつけます。

#### Apache
apache:httpd.confのvirtualhost例

```
<VirtualHost *:80>
 DocumentRoot /virtual/works/public_html/hogehoge_project/public
 ServerName hogehoge_project.com
 SetEnv FUEL_ENV production
</VirtualHost>
```

### 動作確認(staging)環境

#### コマンド
```
FUEL_ENV=staging php oil refine migrate
```
のように、先頭に`FUEL_ENV=staging`をつけます。

#### Apache
apache:httpd.confのvirtualhost例

```
<VirtualHost *:80>
 DocumentRoot /virtual/staging/public_html/hogehoge_project/public
 ServerName staging.hogehoge_project.com
 SetEnv FUEL_ENV staging
</VirtualHost>
```

# 規約

* 原則FuelPHP規約に準ずる http://fuelphp.jp/docs/1.7/general/coding_standards.html
* DBスキーマ設定にはmigrationファイルを利用する(`php oil e ggnerate migration xxxxx`や、`php oil r migrate`)

## fuel/app/assets

* jsもcssもアプリケーションの一部なので、app内に作成
* publicからはシンボリックリンクする
* アプリケーションではないjsはそのままpublicで使う（jQueryなど）
