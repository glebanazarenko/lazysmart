# lazysmart
веб-приложение
для дистанционного управления температурой устройства.
1) пользователь может иметь несколько устройств; 
2) пользователю должны быть доступны информация о каждом из устройств и элементы управления каждым из них (на одной странице);
4) измените структуру базы данных таким образом, чтобы можно было вести учет действий пользователя; 
5) вывод состояния устройства и элементов управления им дополните ссылкой на страницу, демонстрирующую историю управления устройством;
6) напишите триггер, «блокирующий» пользователя в случае чрезмерно частых обращений к устройству;
7) такому пользователю не должны выводится информация о состоянии таргетируемого устройства и элементы управления им;
8) напишите триггер, «блокирующий» устройство в случае чрезмерно частых обращений к серверу; 
9) пользователю должна демонстрироваться информация о подозрительном поведении устройства.

Есть разные аккаунты с разными устройствами
О каждом устройстве в одном аккаунте имеется история, которая показывается при нажатии специальной кнопки.

Приложение отслеживает как часто юзер обращается к устройству. Если выходит, что он меняет состояние реле более 5 раз в минуту, то ему блокируется доступ к устройству.
Возврат доступа происходит при перезагрузки страницы в новой минуте. 

Добавлена проверка, сколько раз пользователь обноновил страницу. Если за минуту происходит более 20 обновлений, то пользователя кидает на специальную страницу, где он может отдохнуть и вернуться на главную.
