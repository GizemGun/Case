# Case

Postman Collection Link:
https://www.getpostman.com/collections/e4e3068bd2b5fc65101b

Not: İşlemler arasında loginden alacağınız token'ı Authorization kısmından Bearer Token olarak eklemeniz gerekmektedir.

## Projeyi çalıştmak için gerekenler:

- Docker
- Docker-compose
- Makefile

### Docker ve Makefile kurulumu

- `make local_build_first` ile proje sıfırdan kurularak tüm ayarları yapılır ve oluşturulur.
- `make local_rebuild` ile plugin veya ekstra şeyler eklenmişse sıfırdan kurmadan yeniden başlatabilirsiniz
- `make local_remove_container` ile projeyi tamamen silebilirsiniz.
- **ÖNEMLİ NOT** '`local_remove_container`' komutu docker tarafında `aktif olmayan` tüm image ve containerları silecektir.

### Proje giriş bilgileri:

- **Admin kullanıcı bilgileri:**
    - Kullanıcı adı: abc@case.com
    - Şifre: 0123456

- **Müşteri kullanıcı bilgileri:**
    - Kullanıcı 1: user1@case.com
    - Şifre: 556800
    - Kullanıcı 2: user2@case.com
    - Şifre: 123654
    - Kullanıcı 3: user3@case.com
    - Şifre: 0123456

## Proje API bilgileri
- http://localhost:7500/api/login_check linkini kullanarak sisteme giriş yapabilirsiniz. Örnek JSON;
  {
  "username": "abc@case.com",
  "password": "0123456"
  }

- http://localhost:7500/api/rest/get-products linkini kullanarak ürünlere erişebilirsiniz. Bunun için her hangi bir token almanıza gerek yoktur.

- http://localhost:7500/api/rest/create-order linkini kullanarak sipariş oluşturabilirsiniz. Body kısmında;
  {
  "products": [
  {
  "product": 1,
  "quantity": 3
  },
  {
  "product": 2,
  "quantity": 2
  },
  {
  "product": 3,
  "quantity": 4
  }
  ],
  "address": "Ofis Tren Yolu Sk. No:10 D: 1 Diyarbakır/Yenişehir (Bahreyn Cafe'nin üstü)"
  }
  yukarıdaki örnekte olduğu gibi sipariş içeriğinizi ve adresinizi JSON şeklinde göndermeniz gerekmektedir.
  Dönüş olarak "isSuccess": true dönüyorsa başarıyla siparişinizi oluşturduğunuz anlamına gelir.
  NOT: Bu linke ulaşabilmeniz için ROLE_USER yetkisine sahip olmanız gerekmektedir.

- http://localhost:7500/api/rest/update-order linkini kullanarak shippingDate'i girilmemiş siparişlerinizi güncelleyebilirsiniz. Bunun için token eklendikten sonra body kısmına;
  {
  "order": 2, // order id'si
  "orderProducts": [
  {
  "id": 4, // orderProduct id'si
  "quantity": 10
  },
  {
  "id": 5, // orderProduct id'si
  "quantity": 9
  }
  ],
  "address": "Deneme 1-2-3"
  }
  yukarıdaki örnekte olduğu gibi güncellemek istediğiniz siparişinizi ve içeriklerinin id'lerini göndermeniz gerekmektedir.
  Bu şekilde sipariş içeriğinizde sadece miktar ve adres değişikliği yapabilirsiniz.
  NOT: Bu linke ulaşabilmeniz için ROLE_USER yetkisine sahip olmanız gerekmektedir.

- http://localhost:7500/api/rest/get-user-order linkini kullanarak body kısmına boş gönderdiğinizde
  aktif kullanıcıya ait tüm siparişler listelenecektir.
  NOT: Bu linke ulaşabilmeniz için ROLE_USER yetkisine sahip olmanız gerekmektedir.

- http://localhost:7500/api/rest/get-order-detail linkini kullanarak body kısmına;
  {
  "orderCode": "XXXXXXXXXX"
  }
  10 haneli sipariş kodu bilginizi gönderdikten sonra o order'a ait sipariş detayınız listelenecektir.
  NOT: Bu linke ulaşabilmeniz için ROLE_USER yetkisine sahip olmanız gerekmektedir.

- http://localhost:7500/api/rest/update-shipping-date linkinden shippingDate güncellemesi gerçekleştirebilirsinzi. Body kısmına;
  {
  "orderCode": "XXXXXXXXXX",
  "shippingDate": "2022-09-25"
  }
  10 haneli sipariş kodunu ve siparişin teslimat tarihi gönderdiğinizde işlem gerçekleşmiş olacaktır.
  NOT: Bu linke ulaşabilmeniz için ROLE_ADMIN yetkisine sahip olmanız gerekmektedir.