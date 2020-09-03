export default (context, inject) => {
  const images = (img , type) => {
    let t ='-cropped';
    if (type !== undefined){
      t = '-'+type;
    }
    if(type === 'org'){
      t='';
    }
    let imageName = img.split('.');
    return context.env.imageUrl+imageName[0]+t+'.'+imageName[1];
  }
  // Inject $images(msg) in Vue, context and store.
  inject('images', images)
  // For Nuxt <= 2.12, also add 👇
  context.$images = images
}
